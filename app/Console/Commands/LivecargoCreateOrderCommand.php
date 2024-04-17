<?php

namespace App\Console\Commands;

use App\Actions\LiveCargoAddQrCodesAction;
use App\Actions\UserWriteOffBalance;
use App\Enums\PickUpStatus;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Enums\UserPriceType;
use App\Models\LivecargoDelivery;
use App\Models\LivecargoOrder;
use App\Models\PickpointAddress;
use App\Models\PickUp;
use App\Models\Purchase;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WbPickpoints;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;

class LivecargoCreateOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'livecargo:create-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create livecargo order (available for pickup purchases)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testMode = config('mpbtop.livecargo.testmode');
        // $testMode = true;

        $this->info(Purchase::whereHas('user', function (Builder $query) {
            $query->whereJsonContains('preferences->use_livecargo', true);
        })->where('delivery_status', Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP)
            ->where('status', 'done')
            ->where('delivery_qrcode', '!=', '')
            ->count());

        $purchases = Purchase::whereHas('user', function (Builder $query) {
            $query->whereJsonContains('preferences->use_livecargo', true);
        })->where('delivery_status', Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP)
            ->where('status', 'done')
            ->where('delivery_qrcode', '!=', '')
            ->get();

        $zones = [];

        foreach ($purchases as $purchase) {
            $pickpoints = WbPickpoints::where('address', preg_replace('/\s+/', ' ', trim($purchase->address)))->get();

            if ($pickpoints->count() > 1) {
                $this->info('Найдено больше одного адреса #'.$purchase->id);

                continue;
            } elseif ($pickpoints->count() == 0) {
                $this->info('Адрес не найден #'.$purchase->id);

                continue;
            }

            $address = PickpointAddress::where('address', preg_replace('/\s+/', ' ', trim($purchase->address)))->first();

            if (! $address) {
                $this->info('Зона не определена найден #'.$purchase->id);

                continue;
            }

            $livecargoDelivery = LivecargoDelivery::create([
                'deliveryable_id' => $purchase->id,
                'deliveryable_type' => Purchase::class,
            ]);

            $coords = explode(',', $pickpoints[0]->coordinates);

            $zones[$address->pickpoint_zone_id]['routes'][] = [
                'address' => $purchase->address,
                'coords' => [
                    (float) $coords[0],
                    (float) $coords[1],
                ],
                'addressatInfo' => [
                    'phone' => '79999999999',
                ],
                'mpbt_id' => $livecargoDelivery->id,
                'pickpoint_address_id' => $address->id,
            ];
        }

        $pickUps = PickUp::whereHas('user', function (Builder $query) {
            $query->whereJsonContains('preferences->use_livecargo', true);
        })->whereIn('status', [
            PickUpStatus::PENDING,
        ])->whereDate('created_at', now())
            ->get();

        $usersData = [];

        foreach ($pickUps as $pickUp) {
            $pickpoints = WbPickpoints::where('address', preg_replace('/\s+/', ' ', trim($pickUp->address)))->get();

            if (! isset($usersData[$pickUp->user->id])) {
                $userService = new UserService($pickUp->user);

                $usersData[$pickUp->user->id] = [
                    'balance' => $pickUp->user->balance,
                    'to_pay' => 0,
                    'pick_up_price' => $userService->getPrice(UserPriceType::PICK_UP),
                ];
            }

            if ($pickpoints->count() > 1) {
                info('Найдено больше одного адреса #'.$pickUp->id);

                continue;
            } elseif ($pickpoints->count() == 0) {
                info('Адрес не найден #'.$pickUp->id);

                $pickUp->status = PickUpStatus::NOT_FOUND_ADDRESS;
                $pickUp->save();

                continue;
            }

            if ($usersData[$pickUp->user->id]['balance'] - $usersData[$pickUp->user->id]['to_pay'] - $usersData[$pickUp->user->id]['pick_up_price'] < 0) {
                $pickUp->status = PickUpStatus::NOT_ENOUGH_BALANCE;
                $pickUp->save();

                continue;
            }

            $usersData[$pickUp->user->id]['to_pay'] += $usersData[$pickUp->user->id]['pick_up_price'];

            $address = PickpointAddress::where('address', preg_replace('/\s+/', ' ', trim($pickUp->address)))->first();

            if (! $address) {
                info('Зона не определена найден #'.$pickUp->id);

                $pickUp->status = PickUpStatus::UNSUPPORTED_ADDRESS;
                $pickUp->save();

                continue;
            }

            $livecargoDelivery = LivecargoDelivery::create([
                'deliveryable_id' => $pickUp->id,
                'deliveryable_type' => PickUp::class,
            ]);

            $coords = explode(',', $pickpoints[0]->coordinates);

            $pickUp->status = PickUpStatus::PROCESS;
            $pickUp->save();

            $zones[$address->pickpoint_zone_id]['routes'][] = [
                'address' => $pickUp->address,
                'coords' => [
                    (float) $coords[0],
                    (float) $coords[1],
                ],
                'addressatInfo' => [
                    'phone' => '79999999999',
                ],
                'mpbt_id' => $livecargoDelivery->id,
                'pickpoint_address_id' => $address->id,
            ];
        }

        $preparedZones = [];

        foreach ($zones as $key => $zone) {
            $preparedZones[] = [
                'data' => $zone,
                'id' => $key,
            ];
        }

        $zones = $preparedZones;

        if (count($zones) == 0) {
            $this->info('Нет точек для создания заказа');
            info('Livecargo: Нет точек для создания заказа');
            exit;
        }

        $livcargoOrders = [];
        foreach ($zones as $zone) {
            $livecargoOrder = LivecargoOrder::create([
                'pickpoint_zone_id' => $zone['id'],
                'order_id' => '',
                'is_test' => $testMode,
                'data' => '{}',
                'message' => '',
            ]);

            $livecargoOrder->order_id = $livecargoOrder->id;
            $livecargoOrder->data = $zone['data']['routes'];
            $livecargoOrder->status = 1;
            $livecargoOrder->message = '';
            $livecargoOrder->save();
            $livcargoOrders[] = [
                'data' => [
                    'route' => $zone['data']['routes'],
                ],
                'order_id' => $livecargoOrder->order_id,
                'livecargo_order_id' => $livecargoOrder->id,
            ];
        }

        foreach ($livcargoOrders as $orderKey => $order) {
            foreach ($order['data']['route'] as $key => $point) {
                LivecargoDelivery::where('id', $point['mpbt_id'])->update([
                    'livecargo_order_id' => $order['livecargo_order_id'],
                    'pickpoint_address_id' => $point['pickpoint_address_id'],
                ]);
            }
        }

        // $livcargoOrders = [];

        // foreach (LivecargoOrder::where('id', '>', 40)->get() as $livecargoOrder) {
        //     $livcargoOrders[] = [
        //         // 'data' => $lcResponseJson['data'],
        //         'order_id' => $livecargoOrder->order_id,
        //         'livecargo_order_id' => $livecargoOrder->id,
        //     ];
        // }

        foreach ($livcargoOrders as $orderKey => $order) {
            $points = LivecargoDelivery::where('livecargo_order_id', $order['livecargo_order_id'])->get();
            $qrCodes = [];
            foreach ($points as $point) {
                // if ($point->purchase->delivery_ext_qrcode != '') {
                //     $path = storage_path('app/public/'.$point->purchase->delivery_ext_qrcode);
                // } else {
                //     $path = storage_path('app/public/'.$point->purchase->delivery_qrcode);
                // }

                // $type = pathinfo($path, PATHINFO_EXTENSION);
                // $name = pathinfo($path, PATHINFO_FILENAME);
                // $data = file_get_contents($path);
                // $base64 = base64_encode($data);

                // $qrCodes[$point->point_id.'-'.$name] = $base64;

                if ($point->deliveryable->livecargoDeliveries->count() > 1) {
                    info('#'.$point->deliveryable->id.' С прошлых дней, не списываем');

                    continue;
                }

                info('#'.$point->deliveryable->id.' Списываем если не админ');

                if ($point->deliveryable->user->role != User::ROLE_ADMIN && ! $testMode) {
                    $userService = new UserService($point->deliveryable->user);
                    $pickUpPrice = $userService->getPrice(UserPriceType::PICK_UP);

                    // if (in_array($point->deliveryable->user->id, [21, 30, 36, 38, 41, 51, 59, 61, 85, 86, 107, 157, 158, 141])) {
                    //     $lcDeliveryPrice = 60 * 100;
                    // } elseif (in_array($point->deliveryable->user->id, [54])) {
                    //     $lcDeliveryPrice = 150 * 100;
                    // } else {
                    //     $lcDeliveryPrice = config('mpbtop.livecargo.prices.large-sized') * 100;
                    // }

                    Transaction::create([
                        'user_id' => $point->deliveryable->user->id,
                        'target_id' => $order['livecargo_order_id'],
                        'amount' => $pickUpPrice,
                        'type' => TransactionType::WRITE_OFF,
                        'target' => TransactionTarget::LIVECARGO,
                    ]);

                    $userWriteOffBalance = new UserWriteOffBalance();
                    $userWriteOffBalance->handle($point->deliveryable->user, $pickUpPrice);
                }
            }

            // $lcQR = new LiveCargoAddQrCodesAction();
            // $this->info($order['order_id']);

            // $lcQrResponse = $lcQR->handle($livecargoOrder->order_id, $qrCodes);

            // foreach (array_chunk($qrCodes, 10, true) as $part) {
            //     $lcQrResponse = $lcQR->handle($order['order_id'], $part);
            // }
        }
    }
}
