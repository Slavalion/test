<?php

namespace App\Http\Controllers;

use App\Actions\BotRequestAction;
use App\Actions\UserTopUpBalance;
use App\Actions\UserWriteOffBalance;
use App\Enums\PurchaseTypesEnum;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserPriceType;
use App\Enums\UserRole;
use App\Exports\PurchasesExport;
use App\Http\Requests\PurchaseStoreRequest;
use App\Models\Purchase;
use App\Models\PurchaseGroup;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PurchaseController extends Controller
{
    public function list(Request $request): InertiaResponse
    {
        if ($request->has('section')) {
            $section = $request->section ?: 'processing';
        } else {
            $section = 'processing';
        }

        $purchasesQuery = Purchase::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->with('product');

        if ($section == 'processing') {
            $purchasesQuery->where('status', 'processing');
        }

        if ($section == 'unavailable') {
            $purchasesQuery->where('status', 'unavailable');
        }

        if ($section == 'not_enough_w_balance') {
            $purchasesQuery->where('status', 'not_enough_w_balance');
        }

        if ($section == 'pickpoint_overloaded') {
            $purchasesQuery->where('status', 'pickpoint_overloaded');
        }

        if ($section == 'done') {
            $purchasesQuery->where('status', 'done');
        }

        $purchases = $purchasesQuery->paginate(50)->withQueryString();
        $purchaseGroupsIds = $purchases->pluck('group_id')->unique();

        $purchaseGroups = PurchaseGroup::whereIn('id', $purchaseGroupsIds)->orderByDesc('created_at')->get();
        $purchaseGroupsArray = $purchaseGroups->keyBy('id')->toArray();

        foreach ($purchases->items() as $purchase) {
            $purchaseGroupsArray[$purchase->group_id]['purchases'][] = $purchase->toArray();
        }

        return Inertia::render('Purchases', [
            'section' => $section,
            // 'purchaseGroups' => $purchaseGroups,
            'purchaseGroups' => array_values($purchaseGroupsArray),
            'paginator' => $purchases->toArray()['links'],
        ]);
    }

    public function add(
        PurchaseStoreRequest $request,
        BotRequestAction $botRequestAction,
        UserWriteOffBalance $userWriteOffBalance
    ): RedirectResponse|JsonResponse {
        $user = $request->user();

        $userService = new UserService($user);
        $purchasePrice = $userService->getPrice(UserPriceType::PURCHASE);

        DB::beginTransaction();

        $purchaseGroup = PurchaseGroup::create([
            'user_id' => $request->user()->id,
        ]);

        $paymentData = [];

        if ($request->payment_type == 'telegram') {
            $paymentData['chat_id'] = $request->user()->preferences['paymentChatId'];
        }

        $totalServicePrice = $purchasePrice * count($request->products);

        $productsData = [];
        $groupSum = 0;

        if ($totalServicePrice > $request->user()->balance) {
            return response()->json([
                'error' => 'Not enough balance',
            ], 422);
        }

        foreach ($request->products as $product) {
            $task = new Task();
            $task->type = 'wb-purchase';
            $task->status = 'process';
            $task->data = json_encode([
                'product_id' => $product['remote_id'],
                'quantity' => $product['quantity'],
                'gender' => $product['gender'],
                'keywords' => $product['keywords'],
                'address' => $product['address'],
                'public_at' => $product['purchase_at'],
            ]);
            $task->save();

            $purchase = new Purchase();

            $purchase->task_id = $task->id;

            $purchase->user_id = $request->user()->id;
            $purchase->group_id = $purchaseGroup->id;
            $purchase->status = Purchase::STATUS_PROCESSING;

            $purchase->product_id = $product['remote_id'];
            $purchase->quantity = $product['quantity'];
            $purchase->gender = $product['gender']['id'];
            $purchase->size = $product['size'];
            $purchase->keywords = $product['keywords'];
            $purchase->address = $product['address'];
            $purchase->to_decline = $product['to_decline'];
            $purchase->purchase_at = (new Carbon($product['purchase_at']))->timezone('Europe/Moscow');

            if ($request->type == 'pro' && $request->user()->role == UserRole::ADMIN) {
                $purchase->type = PurchaseTypesEnum::PRO;
            } else {
                $purchase->type = PurchaseTypesEnum::DEFAULT;
            }

            if ($request->test_mode && $request->user()->role == UserRole::ADMIN) {
                $purchase->test_mode = $request->test_mode;
            }

            $purchase->save();

            try {
                $data = [
                    'task_id' => $purchase->task_id,
                    'purchase_id' => $purchase->id,
                    'type' => 'wb-purchase',
                    'user_id' => $request->user()->id,
                    'product_id' => $product['remote_id'],
                    'quantity' => $product['quantity'],
                    'gender' => $product['gender']['id'],
                    'size' => $product['size'],
                    'keywords' => $product['keywords'],
                    'address' => $product['address'],
                    'purchase_at' => $purchase->purchase_ts,
                    'purchase_type' => $purchase->type,
                    'test_mode' => $purchase->test_mode,
                    'payment_type' => $request->payment_type,
                    'payment_data' => $paymentData,
                    'use_mpb_pickup_service' => $request->user()->preferences['use_livecargo'],
                ];

                $task->request = $data;
                $task->save();
            } catch (\Throwable $th) {
                //throw $th;
            }

            $productsData[] = [
                'task_id' => $purchase->task_id,
                'purchase_id' => $purchase->id,
                'type' => 'wb-purchase',
                'user_id' => $request->user()->id,
                'product_id' => $product['remote_id'],
                'quantity' => $product['quantity'],
                'gender' => $product['gender']['id'],
                'size' => $product['size'],
                'keywords' => $product['keywords'],
                'address' => $product['address'],
                'purchase_at' => $purchase->purchase_ts,
                'purchase_type' => $purchase->type,
                'test_mode' => $purchase->test_mode,
                'payment_type' => $request->payment_type,
                'payment_data' => $paymentData,
                'use_mpb_pickup_service' => $request->user()->preferences['use_livecargo'],
            ];

            $groupSum += $purchase->quantity * $purchase->product->price;
        }

        $purchaseGroup->total_sum = $groupSum / 100;
        $purchaseGroup->save();

        if ($request->user()->role != UserRole::ADMIN) {
            Transaction::create([
                'user_id' => $request->user()->id,
                'target_id' => $purchaseGroup->id,
                'amount' => $totalServicePrice,
                'type' => TransactionType::WRITE_OFF,
                'target' => TransactionTarget::PURCHASE,
            ]);

            $userWriteOffBalance->handle($request->user(), $totalServicePrice);
        }

        $botResponse = $botRequestAction->execute($productsData);

        if (! $botResponse) {
            DB::rollBack();

            return response()->json([
                'message' => 'Не удалось создать выкупы, попробуйте позже',
            ], 422);
        }

        DB::commit();

        return redirect()->route('purchase.list');
    }

    public function destroy(Request $request, BotRequestAction $botRequestAction): JsonResponse
    {
        $request->validate([
            'purchase_id' => ['required'],
        ]);

        // if ($request->user()->role != User::ROLE_ADMIN) {
        //     throw ValidationException::withMessages([
        //         'permission' => 'Вы не можете удалять выкупы',
        //     ]);
        // }

        $purchase = Purchase::findOrFail($request->purchase_id);

        if ($purchase->status != Purchase::STATUS_PROCESSING) {
            throw ValidationException::withMessages([
                'status' => 'Можно удалять только выкупы "в работе"',
            ]);
        }

        if ($purchase->purchase_at->lte(now())) {
            throw ValidationException::withMessages([
                'status' => 'Можно удалять выкупы только до даты выкупа',
            ]);
        }

        $botRequestAction->execute([
            'task_id' => $purchase->task_id,
            'purchase_id' => $purchase->id,
            'type' => 'wb-purchase-delete',
            'user_id' => $purchase->user_id,
        ]);

        $user = $purchase->user;

        $userService = new UserService($user);
        $purchasePrice = $userService->getPrice(UserPriceType::PURCHASE);

        // if (in_array($user->id, [21, 30, 51, 61])) {
        //     $totalServicePrice = 45 * 100;
        // } elseif (in_array($user->id, [36, 38, 41, 54, 85, 86])) {
        //     $totalServicePrice = 40 * 100;
        // } else {
        //     $totalServicePrice = config('mpbtop.prices.purchase') * 100;
        // }

        $totalServicePrice = $purchasePrice;

        DB::transaction(function () use ($purchase, $totalServicePrice) {
            Transaction::create([
                'user_id' => $purchase->user->id,
                'target_id' => $purchase->id,
                'amount' => $totalServicePrice,
                'type' => TransactionType::TOP_UP,
                'target' => TransactionTarget::RETURN_PURCHASE,
            ]);

            $purchase->task->delete();
            $purchase->delete();
        });

        (new UserTopUpBalance)->handle($user, $totalServicePrice);

        return response()->json([], 200);
    }

    public function download(Request $request): BinaryFileResponse
    {
        // $request->validate([
        //     'status' => Rule::in([...self::deliveryStatuses, 'all']),
        // ]);

        $status = $request->status ?: 'all';

        return (new PurchasesExport($request->user()->id, $status))->download('purchases.xlsx');
    }
}
