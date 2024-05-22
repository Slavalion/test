<?php

namespace App\Http\Controllers\Api;

use App\Actions\LiveCargoAddQrCodesAction;
use App\Actions\SendUserNotificationAction;
use App\Actions\UserTopUpBalance;
use App\Enums\PurchaseTypesEnum;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserPriceType;
use App\Events\Courier\DeliveryUpdateEvent;
use App\Events\DeliveryUpdateDataEvent;
use App\Events\DeliveryUpdateTotalEvent;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Task;
use App\Models\Transaction;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    private const purchaseStatuses = [
        'pending' => Purchase::STATUS_PENDING,
        'processing' => Purchase::STATUS_PROCESSING,
        'unavailable' => Purchase::STATUS_UNAVAILABLE,
        'not_enough_w_balance' => Purchase::STATUS_NOT_ENOUGH_W_BALANCE,
        'pickpoint_overloaded' => Purchase::STATUS_PICKPOINT_OVERLOADED,
        'done' => Purchase::STATUS_DONE,
    ];

    private const deliveryStatuses = [
        'none' => Purchase::DELIVERY_STATUS_NONE,
        'processing' => Purchase::DELIVERY_STATUS_PROCESSING,
        'sorted' => Purchase::DELIVERY_STATUS_SORTED,
        'pending' => Purchase::DELIVERY_STATUS_PENDING,
        'on_the_way' => Purchase::DELIVERY_STATUS_ON_THE_WAY,
        'available_for_pick_up' => Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP,
        'picked_up' => Purchase::DELIVERY_STATUS_PICKED_UP,
        'canceled' => Purchase::DELIVERY_STATUS_CANCELED,
        'not_paid' => Purchase::DELIVERY_STATUS_NOT_PAID,
    ];

    private const deliveryStatusesText = [
        'none' => 'NONE',
        'processing' => 'Оплачен',
        'sorted' => 'Отсортирован',
        'pending' => 'В работе',
        'on_the_way' => 'В пути',
        'available_for_pick_up' => 'Доступен к выдаче',
        'picked_up' => 'Забран',
        'canceled' => 'Отменен',
        'not_paid' => 'Не оплачен',
    ];

    public function update(Request $request, UserTopUpBalance $userTopUpBalance)
    {
        $task = Task::find($request->task_id);

        if (! $task) {
            return response()->json(['bad'], 400);
        }

        $purchase = Purchase::where('task_id', $task->id)->first();

        if (! $purchase) {
            return response()->json(['bad'], 400);
        }

        if ($request->has('status') && in_array($request->status, self::purchaseStatuses)) {
            $prevStatus = $purchase->status;

            $purchase->status = self::purchaseStatuses[$request->status];
            $purchase->save();

            if (
                $purchase->status == Purchase::STATUS_NOT_ENOUGH_W_BALANCE
                && $prevStatus != Purchase::STATUS_NOT_ENOUGH_W_BALANCE
            ) {
                $user = $purchase->user;

                $userService = new UserService($user);
                $purchasePrice = $userService->getPrice(UserPriceType::PURCHASE);

                $totalServicePrice = $purchasePrice;

                Transaction::create([
                    'user_id' => $user?->id,
                    'target_id' => $purchase->id,
                    'amount' => $totalServicePrice,
                    'type' => TransactionType::TOP_UP,
                    'target' => TransactionTarget::RETURN_PURCHASE,
                ]);

                $userTopUpBalance->handle($user, $totalServicePrice);
            }

            if (
                $purchase->status == Purchase::STATUS_PICKPOINT_OVERLOADED
                && $prevStatus != Purchase::STATUS_PICKPOINT_OVERLOADED
            ) {
                $user = $purchase->user;

                $userService = new UserService($user);
                $purchasePrice = $userService->getPrice(UserPriceType::PURCHASE);

                $totalServicePrice = $purchasePrice;

                Transaction::create([
                    'user_id' => $user?->id,
                    'target_id' => $purchase->id,
                    'amount' => $totalServicePrice,
                    'type' => TransactionType::TOP_UP,
                    'target' => TransactionTarget::RETURN_PURCHASE,
                ]);

                $userTopUpBalance->handle($user, $totalServicePrice);
            }

            if (
                $purchase->status == Purchase::STATUS_UNAVAILABLE
                && $prevStatus != Purchase::STATUS_UNAVAILABLE
            ) {
                $user = $purchase->user;

                $userService = new UserService($user);
                $purchasePrice = $userService->getPrice(UserPriceType::PURCHASE);

                $totalServicePrice = $purchasePrice;

                Transaction::create([
                    'user_id' => $user?->id,
                    'target_id' => $purchase->id,
                    'amount' => $totalServicePrice,
                    'type' => TransactionType::TOP_UP,
                    'target' => TransactionTarget::RETURN_PURCHASE,
                ]);

                $userTopUpBalance->handle($user, $totalServicePrice);
            }
        }

        if ($request->has('search_position')) {
            $purchase->search_position = (int) $request->search_position;
            $purchase->save();
        }

        if ($request->has('quantity') && $purchase->type == PurchaseTypesEnum::PRO) {
            $purchase->quantity = (int) $request->quantity;
            $purchase->save();
        }

        return response()->json(['ok']);
    }

    public function updateDelivery(Request $request)
    {
        $purchase = Purchase::where('task_id', $request->task_id)->first();

        if (! $purchase) {
            return response()->json(['bad' => 'Не найден выкуп с таким таск ид'], 400);
        }

        if ($request->delivery_status) {
            if ($request->delivery_status == self::deliveryStatuses['available_for_pick_up']) {
                if ($purchase->ready_to_pick_up_at == null) {
                    $purchase->ready_to_pick_up_at = now();
                }
            }

            try {
                $notifyWallet = $purchase->user->preferences['notifications']['purchase.all'] ?? false;
                $deliveryStatusText = self::deliveryStatusesText[$request->delivery_status] ?? null;

                if ($purchase->delivery_status != $request->delivery_status && $notifyWallet && $deliveryStatusText) {

                    (new SendUserNotificationAction())->handle(
                        $purchase->user,
                        'Выкуп '.$purchase->task_id.' сменил статус на '.$deliveryStatusText
                    );
                }
            } catch (\Throwable $th) {
                info('Ошибка при попытке отправки сообщения о смене статуса');
                info($th->getMessage());
            }

            $purchase->delivery_status = $request->delivery_status;
        }

        if ($request->receipt) {
            $purchase->receipt = $request->receipt;
        }

        if ($request->price) {
            $purchase->price = $request->price * 100;
        }

        if ($request->picked_up_at) {
            $purchase->picked_up_at = Carbon::createFromDate($request->picked_up_at);
        }

        if ($request->delivery_pin) {
            $purchase->delivery_pin = $request->delivery_pin;
        }

        if ($request->delivery_phone) {
            $purchase->delivery_phone = $request->delivery_phone;
        }

        if ($request->delivery_name) {
            $purchase->delivery_name = $request->delivery_name;
        }

        if ($request->address && trim($request->address) != '') {
            if ($purchase->original_address == '') {
                $purchase->original_address = $purchase->address;
            }

            $purchase->address = $request->address;
        }

        if ($request->delivery_qrcode) {
            $path = 'qrcodes/'.bin2hex('qr-code-'.$purchase->id).'.png';
            Storage::disk('public')->put($path, base64_decode(str_replace(' ', '+', $request->delivery_qrcode)), 'public');
            $purchase->delivery_qrcode = $path;
        }

        if ($request->delivery_ext_qrcode) {
            $path = 'qrcodes/ext-'.bin2hex('qr-code-'.$purchase->id).'.png';
            Storage::disk('public')->put($path, base64_decode(str_replace(' ', '+', $request->delivery_ext_qrcode)), 'public');
            $purchase->delivery_ext_qrcode = $path;

            // if ($purchase->livecargoDelivery) {
            //     $lcQR = new LiveCargoAddQrCodesAction();
            //     $lcQrResponse = $lcQR->handle($purchase->livecargoDelivery->livecargoOrder->order_id, [
            //         $purchase->livecargoDelivery->point_id.'-ext-'.bin2hex('qr-code-'.$purchase->id) => str_replace(' ', '+', $request->delivery_ext_qrcode),
            //     ]);
            // }
        }

        $purchase->save();

        try {
            if ($request->updated) {
                $purchase->is_updating_delivery = false;
                DeliveryUpdateDataEvent::dispatch($purchase->user, $purchase);

                $lcDelivery = $purchase->livecargoDelivery()->whereDate('created_at', now())->first();

                if ($lcDelivery) {
                    DeliveryUpdateEvent::dispatch($purchase->user, $lcDelivery);
                }
            }

            $purchase->save();

            if (
                $request->delivery_status == Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP
                || $request->delivery_status == Purchase::DELIVERY_STATUS_PICKED_UP
            ) {
                $purchase->delivery_status_updated_at = now();
                $purchase->save();
                DeliveryUpdateDataEvent::dispatch($purchase->user, $purchase);
                DeliveryUpdateTotalEvent::dispatch($purchase->user);
            }
        } catch (\Throwable $th) {
            info($th);
        }

        return response()->json(['ok']);
    }
}
