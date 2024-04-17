<?php

namespace App\Http\Controllers\Api;

use App\Actions\UserWriteOffBalance;
use App\Enums\PickUpStatus;
use App\Http\Controllers\Controller;
use App\Models\PickpointAddress;
use App\Models\PickUp;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Services\WildberriesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PickUpController extends Controller
{
    public function import(Request $request): JsonResponse
    {
        if (! $request->header('token')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Api ключ не указан',
            ], 422);
        }

        $token = $request->header('token');

        info('#Тригер апи импорта');
        info($request);

        $user = User::where('api_key', $token)->first();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Api ключ не действителен',
            ], 422);
        }

        $successfulyAdded = 0;
        $errors = [];

        foreach ($request->items as $key => $item) {
            if (! isset($item['external_id']) || trim($item['external_id']) == '') {
                continue;
            }

            // $externalId = strval($key);
            $externalId = $item['external_id'];

            if (! isset($item['product_code']) || trim($item['product_code']) == '') {
                $errors[$externalId]['empty_product_code'] = 'Код товара не указан';
            }

            if (! isset($item['phone']) || trim($item['phone']) == '') {
                $errors[$externalId]['empty_phone'] = 'Телефон не указан';
            }

            if (! isset($item['code']) || trim($item['code']) == '') {
                $errors[$externalId]['empty_code'] = 'Код получения не указан';
            }

            if (! isset($item['address']) || trim($item['address']) == '') {
                $errors[$externalId]['empty_address'] = 'Адрес не указан';
            }

            if (isset($errors[$externalId])) {
                continue;
            }

            $product = Product::where('remote_id', $item['product_code'])->first();

            if (! $product) {
                $wbService = new WildberriesService($item['product_code']);
                $productData = $wbService->getProduct();

                if (! $productData) {
                    $errors[$externalId]['product_not_found'] = 'Товар с таким артикулом не найден';

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

            $address = PickpointAddress::where('address', preg_replace('/\s+/', ' ', trim($item['address'])))->first();

            if (! $address) {
                $errors[$externalId]['wrong_address'] = 'Адресс не поддерживается';

                continue;
            }

            if (PickUp::where('user_id', $user->id)->where('external_id', $item['external_id'])->exists()) {
                $errors[$externalId]['already_exists'] = 'Забор с таки external id уже существует';

                continue;
            }

            $pickUpEntity = PickUp::create([
                'user_id' => $user->id,
                'external_id' => $item['external_id'],
                'product_id' => $item['product_code'],
                'address' => $item['address'],
                'phone' => $item['phone'],
                'code' => $item['code'],
                'status' => PickUpStatus::PENDING,
            ]);

            // if ($user->role != User::ROLE_ADMIN) {

            //     // if (in_array($request->user()->id, [36, 38, 41, 54])) {
            //     //     $reviewPrice = 20 * 100;
            //     // } elseif (in_array($request->user()->id, [30, 51])) {
            //     //     $reviewPrice = 40 * 100;
            //     // } else {
            //     $reviewPrice = 60 * 100;
            //     // }

            //     Transaction::create([
            //         'user_id' => $request->user()->id,
            //         'target_id' => $pickUpEntity->id,
            //         'amount' => $reviewPrice,
            //         'type' => Transaction::TYPE_WRITE_OFF,
            //         'target' => Transaction::TARGET_PICK_UP,
            //     ]);

            //     $userWriteOffBalance = new UserWriteOffBalance();
            //     $userWriteOffBalance->handle($request->user(), $reviewPrice);
            // }

            $successfulyAdded++;
        }

        return response()->json([
            'status' => 'ok',
            'successfuly_added' => $successfulyAdded,
            'errors' => $errors,
        ]);
    }
}
