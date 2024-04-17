<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LivecargoDelivery;
use App\Models\LivecargoOrder;
use App\Models\PickpointAddress;
use App\Models\Purchase;
use App\Models\WbPickpoints;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class LivecargoController extends Controller
{
    public function index(): InertiaResponse
    {
        $livecargoOrders = LivecargoOrder::select('id')->whereDate('created_at', now()::today())->get();

        $livecargoDeliveries = [];

        if ($livecargoOrders) {
            $livecargoDeliveries = LivecargoDelivery::whereIn('livecargo_order_id', $livecargoOrders->pluck('id'))
                ->with(['deliveryable', 'deliveryable.product'])
                ->orderBy('picked_up_status')
                ->get();
        }

        return Inertia::render('Admin/LivecargoPage', [
            'pickUpDeliveries' => $livecargoDeliveries,
        ]);
    }

    public function indexOld(Request $request): InertiaResponse
    {
        $pendingToLivecargo = Purchase::has('livecargoDelivery', 0)
            ->whereHas('user', function (Builder $query) {
                $query->whereJsonContains('preferences->use_livecargo', true);
            })
            ->where('delivery_status', Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP)
            ->where('delivery_qrcode', '!=', '')
            ->count();

        $pendingToLivecargoTotal = Purchase::whereHas('user', function (Builder $query) {
            $query->whereJsonContains('preferences->use_livecargo', true);
        })
            ->where('delivery_status', Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP)
            ->where('delivery_qrcode', '!=', '')
            ->count();

        $missing = [];
        $allGood = [];

        $missAddresses = [];
        $missingZoneCounter = 0;

        $purchases = Purchase::has('livecargoDelivery', 0)
            ->whereHas('user', function (Builder $query) {
                $query->whereJsonContains('preferences->use_livecargo', true);
            })
            ->where('delivery_status', Purchase::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP)
            ->where('delivery_ext_qrcode', '!=', '')
            ->limit(10)
            ->get();

        foreach ($purchases as $purchase) {
            $pickpoints = WbPickpoints::where('address', preg_replace('/\s+/', ' ', trim($purchase->address)))->get();

            if ($pickpoints->count() > 1) {
                $missing[] = [
                    'error' => 'Найдено больше одного адреса адрес вб',
                    ...$purchase->toArray(),
                ];

                continue;
            } elseif ($pickpoints->count() == 0) {
                $missing[] = [
                    'error' => 'Не найден такой адрес вб',
                    ...$purchase->toArray(),
                ];

                continue;
            }

            $address = PickpointAddress::where('address', preg_replace('/\s+/', ' ', trim($purchase->address)))->first();

            if (! $address) {
                $missing[] = [
                    'error' => 'Не удалось определить зону',
                    ...$purchase->toArray(),
                ];

                $missAddresses[] = $purchase->address;
                $missingZoneCounter++;

                continue;
            }

            $allGood[] = [
                'error' => false,
                ...$purchase->toArray(),
            ];
        }

        return Inertia::render('Admin/LivecargoPage', [
            'deliveries' => [
                ...$missing,
                ...$allGood,
            ],
            'missingAddresses' => [...array_unique($missAddresses)],
            'missingZoneCounter' => $missingZoneCounter,
            'pendingDeliveries' => $pendingToLivecargo,
            'pendingToLivecargoTotal' => $pendingToLivecargoTotal,
        ]);
    }
}
