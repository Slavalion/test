<?php

namespace App\Services;

use App\Enums\PurchaseTypesEnum;
use App\Enums\UserPriceType;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseGroup;
use App\Models\Task;
use App\Models\User;
use App\Models\WbPickpoints;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DemoUserService
{
    public function createUser($ip = null): User
    {
        $userName = Str::random(8);

        $user = User::create([
            'name' => $userName,
            'email' => $userName.'@mpb.top',
            'balance' => 33000 * 100,
            'password' => Str::random(16),
            'ip' => $ip,
        ]);

        return $user;
    }

    public function createPurchases(User $user): void
    {
        $userService = new UserService($user);

        $demoProducts = [
            0 => [
                'id' => 164462461,
                'keywords' => [
                    'Запрос 1',
                    'Запрос 2',
                ],
            ],
            1 => [
                'id' => 157839561,
                'keywords' => [
                    'Запрос 3',
                    'Запрос 4',
                ],
            ],
        ];

        $demoGroups = [
            [
                'product_key' => 0,
                'purchase_count' => 5,
            ],
            [
                'product_key' => 1,
                'purchase_count' => 7,
            ],
        ];

        $totalWbPickpoints = WbPickpoints::count();

        foreach ($demoGroups as $group) {
            $demoProduct = $demoProducts[$group['product_key']];
            $wbProduct = $this->getWbProduct($demoProduct['id']);

            if (! $wbProduct) {
                continue;
            }

            $purchaseGroup = PurchaseGroup::create([
                'user_id' => $user->id,
                'total_sum' => $group['purchase_count'] * $userService->getPrice(UserPriceType::PURCHASE),
            ]);

            $randomArray = [];

            for ($i = 0; $i < $group['purchase_count']; $i++) {
                $randomArray[] = rand(1, $totalWbPickpoints);
            }

            $addresses = WbPickpoints::select('address')->whereIn('id', $randomArray)->get()->pluck('address');

            for ($i = 0; $i < $group['purchase_count']; $i++) {

                $purchaseAt = now()->addMinutes(rand(0, 15))->timezone('Europe/Moscow');
                $keywords = $demoProduct['keywords'][rand(0, count($demoProduct['keywords']) - 1)];

                // Create purchase task
                $task = new Task();
                $task->user_id = $user->id;
                $task->type = 'wb-purchase';
                $task->status = 'process';
                $task->data = json_encode([
                    'product_id' => $demoProduct['id'],
                    'quantity' => 1,
                    'gender' => 1,
                    'keywords' => $keywords,
                    'address' => $addresses[$i],
                    'public_at' => $purchaseAt,
                ]);
                $task->save();

                $purchase = new Purchase();

                $purchase->task_id = $task->id;

                $purchase->user_id = $user->id;
                $purchase->group_id = $purchaseGroup->id;
                $purchase->status = Purchase::STATUS_PROCESSING;

                $purchase->product_id = $demoProduct['id'];
                $purchase->quantity = 1;
                $purchase->gender = 1;
                $purchase->size = ['id' => null, 'name' => null];
                $purchase->keywords = $keywords;
                $purchase->address = $addresses[$i];
                $purchase->purchase_at = $purchaseAt;

                $purchase->type = PurchaseTypesEnum::DEFAULT;

                $purchase->save();
            }
        }
    }

    private function getWbProduct($productCode)
    {
        $product = Product::where('remote_id', $productCode)->first();

        $response = Http::get('https://card.wb.ru/cards/detail?appType=1&curr=rub&dest=-1257786&nm='.$productCode);
        $responseData = $response->json();

        if (! isset($responseData['data']['products'][0])) {
            return false;
        }

        $productData = $responseData['data']['products'][0];

        if (! $product) {
            $product = new Product();
        }

        $product->remote_id = $productData['id'];
        $product->price = $productData['salePriceU'];
        $product->name = $productData['name'];
        $product->brand = $productData['brand'];
        $product->image = '';

        $product->save();

        return $product;
    }
}
