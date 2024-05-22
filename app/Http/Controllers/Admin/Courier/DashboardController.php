<?php

namespace App\Http\Controllers\Admin\Courier;

use App\Enums\LivecargoDeliveryPickedUpEnum;
use App\Enums\UserRole;
use App\Exports\Admin\CourierExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\CourierResource;
use App\Models\LivecargoDelivery;
use App\Models\LivecargoOrder;
use App\Models\PickUp;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DashboardController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $couriers = User::where('role', UserRole::COURIER)
            ->withCount([
                'livecargoDeliveries as total_picked_up' => function (Builder $query) {
                    $query->where('picked_up_status', LivecargoDeliveryPickedUpEnum::PICKED_UP)
                        ->whereDate('created_at', now());
                },
                'livecargoDeliveries as total_external' => function (Builder $query) {
                    $query->whereMorphedTo('deliveryable', PickUp::class)
                        ->where('picked_up_status', LivecargoDeliveryPickedUpEnum::PICKED_UP)
                        ->whereDate('created_at', now());
                },
                'livecargoDeliveries as total_main' => function (Builder $query) {
                    $query->whereMorphedTo('deliveryable', Purchase::class)
                        ->where('picked_up_status', LivecargoDeliveryPickedUpEnum::PICKED_UP)
                        ->whereDate('created_at', now());
                },
                'livecargoDeliveries as total_main_wb_approved' => function (Builder $query) {
                    $query->whereMorphedTo('deliveryable', Purchase::class)
                        ->whereMorphRelation('deliveryable', Purchase::class, function (Builder $query) {
                            $query->where('delivery_status', Purchase::DELIVERY_STATUS_PICKED_UP);
                        })
                        ->where('picked_up_status', LivecargoDeliveryPickedUpEnum::PICKED_UP)
                        ->whereDate('created_at', now());
                },
            ])
            ->get();

        $totalDeliveries = LivecargoDelivery::whereDate('created_at', now())->where('deliveryable_type', Purchase::class)->count();
        $byPickUpStatusDeliveries = LivecargoDelivery::select('picked_up_status')
            ->selectRaw('count(picked_up_status) as count')
            ->whereDate('created_at', now())
            ->where('deliveryable_type', Purchase::class)
            ->groupBy('picked_up_status')
            ->get();

        $todayPickUPs = LivecargoDelivery::whereDate('created_at', now())->where('deliveryable_type', PickUp::class)->count();
        $byPickUpStatusPickUPs = LivecargoDelivery::select('picked_up_status')
            ->selectRaw('count(picked_up_status) as count')
            ->whereDate('created_at', now())
            ->where('deliveryable_type', PickUp::class)
            ->groupBy('picked_up_status')
            ->get();

        $orders = LivecargoOrder::whereDate('created_at', now())
            ->with(['pickpointZone'])
            ->withCount(['livecargoDeliveries'])
            ->get();

        $warningDeliveries = LivecargoDelivery::whereDate('created_at', now())
            ->where('deliveryable_type', Purchase::class)
            ->whereMorphRelation('deliveryable', Purchase::class, 'ready_to_pick_up_at', '<=', now()->subDays(10))
            ->whereMorphRelation('deliveryable', Purchase::class, 'ready_to_pick_up_at', '>=', now()->subDays(21))
            ->with('deliveryable')
            ->with('pickpointAddress')
            ->get();

        return Inertia::render('Admin/Courier/DashboardPage', [
            'couriers' => CourierResource::collection($couriers)->resolve(),
            'deliveryStats' => [
                'total' => $totalDeliveries,
                ...$byPickUpStatusDeliveries->mapWithKeys(function (LivecargoDelivery $el) {
                    return [
                        $el->picked_up_status->name => $el->count,
                    ];
                }),
            ],
            'pickUpStats' => [
                'total' => $todayPickUPs,
                ...$byPickUpStatusPickUPs->mapWithKeys(function (LivecargoDelivery $el) {
                    return [
                        $el->picked_up_status->name => $el->count,
                    ];
                }),
            ],
            'orders' => $orders,
            'warningDeliveries' => $warningDeliveries,
        ]);
    }

    public function downloadStat(Request $request, User $courier): BinaryFileResponse
    {
        return (new CourierExport($courier->id))->download('courier-'.$courier->id.'-'.now()->toDateString().'.xlsx');
    }

    public function setPreference(Request $request, User $courier): JsonResponse
    {
        if ($courier->role != UserRole::COURIER) {
            return response()->json([
                'message' => 'Пользователь не является курьером',
            ], 422);
        }

        $courierPreferences = $courier->preferences;

        $courier->preferences = [
            ...$courierPreferences,
            ...$request->only('is_active_courier'),
        ];

        $courier->save();

        return response()->json(['ok']);
    }
}
