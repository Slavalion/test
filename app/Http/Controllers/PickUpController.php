<?php

namespace App\Http\Controllers;

use App\Enums\PickUpStatus;
use App\Enums\UserPriceType;
use App\Imports\PickUpImport;
use App\Models\PickpointAddress;
use App\Models\PickUp;
use App\Models\Product;
use App\Services\UserService;
use App\Services\WildberriesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Maatwebsite\Excel\Facades\Excel;

class PickUpController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $request->validate([
            'status' => Rule::in([
                'pending',
                'process',
                'done',
                'all',
            ]),
        ]);

        $section = $request->section ?: 'process';

        $pickUpsQuery = PickUp::where('user_id', $request->user()->id)->with('product')->orderByDesc('created_at');

        if ($section == 'pending') {
            $pickUpsQuery->where('status', PickUpStatus::PENDING);
        }

        if ($section == 'process') {
            $pickUpsQuery->where('status', PickUpStatus::PROCESS);
        }

        if ($section == 'done') {
            $pickUpsQuery->where('status', PickUpStatus::DONE);
        }

        $pickUpsPaginator = $pickUpsQuery->paginate(50)->withQueryString();

        $pendingCount = PickUp::where('user_id', $request->user()->id)->where('status', PickUpStatus::PENDING)->count();

        $userService = new UserService($request->user());

        return Inertia::render('PickUps', [
            'pickUps' => $pickUpsPaginator->items(),
            'paginator' => $pickUpsPaginator->toArray()['links'],
            'section' => $section,
            'pendingTotalSum' => $pendingCount * $userService->getPrice(UserPriceType::PICK_UP),
        ]);
    }

    public function destroy(PickUp $pickUp): JsonResponse
    {
        if (now()->greaterThan('11:15')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Заборы ожидающие обработки можно удалять до 10:55',
            ], 422);
        }

        $pickUp->delete();

        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        if (now()->greaterThan(now()->setTimeFromTimeString('11:20'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Заявки на забор принимаются до 10:55',
            ], 422);
        }

        if (PickUp::where('user_id', $request->user()->id)->whereDate('created_at', now())->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Вы уже загрузили файл импорта',
            ], 422);
        }

        $errors = [
            'product_not_found' => 0,
            'wrong_address' => 0,
            'empty_phone' => 0,
            'empty_code' => 0,
            'empty_qr_code' => 0,
        ];

        $rows = Excel::toCollection(new PickUpImport, $request->file);
        $rows = $rows[0];
        $rows->shift()->all();

        foreach ($rows as $row) {
            if (trim($row[0]) == '') {
                continue;
            }

            $product = Product::where('remote_id', $row[0])->first();

            if (! $product) {
                $wbService = new WildberriesService($row[0]);
                $productData = $wbService->getProduct();

                if (! $productData) {
                    $errors['product_not_found']++;

                    continue;
                }

                $product = new Product();

                $product->remote_id = $productData['id'];
                $product->price = $productData['salePriceU'];
                $product->name = $productData['name'];
                $product->brand = $productData['brand'];
                $product->image = '';

                $product->save();
            }

            $address = PickpointAddress::where('address', preg_replace('/\s+/', ' ', trim($row[1])))->first();

            if (! $address) {
                $errors['wrong_address']++;
                $errors['missing_addresses'][] = trim($row[1]);

                continue;
            }

            if (! $row[2]) {
                $errors['empty_phone']++;

                continue;
            }

            if (! $row[3]) {
                $errors['empty_code']++;

                continue;
            }

            if (! $row[4]) {
                $errors['empty_qr_code']++;

                continue;
            }

            PickUp::create([
                'user_id' => $request->user()->id,
                'product_id' => $row[0],
                'address' => $row[1],
                'phone' => $row[2],
                'code' => $row[3],
                'qr_code_link' => $row[4],
                'to_decline' => isset($row[5]) && Str::lower($row[5]) == 'да',
                'status' => PickUpStatus::PENDING,
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'errors' => $errors,
        ]);
    }
}
