<?php

namespace App\Http\Controllers;

use App\Actions\RecheckPurchaseAction;
use App\Enums\LivecargoDeliveryPickedUpEnum;
use App\Enums\PickUpStatus;
use App\Enums\UserRole;
use App\Models\LivecargoDelivery;
use App\Models\LivecargoOrder;
use App\Models\PickpointAddress;
use App\Models\PickpointZones;
use App\Models\PickUp;
use App\Models\Purchase;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PublicDeiveriesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role == UserRole::COURIER && ! ($request->user()->preferences['is_active_courier'] ?? false)) {
            abort(403);
        }

        $zone = PickpointZones::findOrFail($request->zone);
        $pickpointAddress = PickpointAddress::findOrFail($request->address);

        $lcOrder = LivecargoOrder::whereDate('created_at', $request->date)->where('pickpoint_zone_id', $zone->id)->firstOrFail();
        $deliveries = $lcOrder->livecargoDeliveries()->where('pickpoint_address_id', $request->address)->with(['deliveryable', 'deliveryable.product'])->get();

        $deliveriesByPhone = [];
        $completedDeliveries = [];
        $completedExternalDeliveries = [];
        $missedExternalDeliveries = [];
        $completedDeliveriesNotApproved = [];

        foreach ($deliveries as $delivery) {
            if (! $delivery->deliveryable) {
                continue;
            }

            $deliveryPhone = match (get_class($delivery->deliveryable)) {
                Purchase::class => $delivery->deliveryable->delivery_phone,
                PickUp::class => $delivery->deliveryable->phone,
            };

            $deliveryType = match (get_class($delivery->deliveryable)) {
                Purchase::class => 'mpbtop',
                PickUp::class => 'outside',
            };

            $delivery->version = now()->format('Ymd');
            $delivery->type = $deliveryType;

            if ($delivery->picked_up_status != LivecargoDeliveryPickedUpEnum::PENDING) {
                if ($delivery->deliveryable instanceof PickUp) {
                    if ($delivery->picked_up_status == LivecargoDeliveryPickedUpEnum::PICKED_UP) {
                        $completedExternalDeliveries[$deliveryPhone][] = $delivery;
                    } else {
                        $missedExternalDeliveries[$deliveryPhone][] = $delivery;
                    }
                }

                if ($delivery->deliveryable instanceof Purchase) {
                    if ($delivery->deliveryable->delivery_status == Purchase::DELIVERY_STATUS_PICKED_UP) {
                        $completedDeliveries[$deliveryPhone][] = $delivery;
                    } else {
                        $completedDeliveriesNotApproved[$deliveryPhone][] = $delivery;
                    }
                }
            } else {
                $deliveriesByPhone[$deliveryPhone][] = $delivery;
            }
        }

        return Inertia::render('Curier/Deliveries', [
            'zone' => $zone,
            'address' => $pickpointAddress,
            'deliveries' => (object) $deliveriesByPhone,
            'completedDeliveries' => (object) $completedDeliveries,
            'completedExternalDeliveries' => (object) $completedExternalDeliveries,
            'missedExternalDeliveries' => (object) $missedExternalDeliveries,
            'completedDeliveriesNotApproved' => (object) $completedDeliveriesNotApproved,
            'totalDeliveries' => $deliveries->count(),
        ]);
    }

    public function zoneList(Request $request)
    {
        if ($request->user()->role == UserRole::COURIER && ! ($request->user()->preferences['is_active_courier'] ?? false)) {
            abort(403);
        }

        $orders = LivecargoOrder::whereDate('created_at', Carbon::today())
            ->with(['pickpointZone'])
            ->withCount(['livecargoDeliveries'])
            ->get();

        return Inertia::render('Curier/Zones', [
            'orders' => $orders,
            'date' => now()->format('Y.m.d'),
        ]);
    }

    public function addressList(Request $request)
    {
        if ($request->user()->role == UserRole::COURIER && ! ($request->user()->preferences['is_active_courier'] ?? false)) {
            abort(403);
        }

        $zone = PickpointZones::findOrFail($request->zone);
        $lcOrder = LivecargoOrder::whereDate('created_at', $request->date)->where('pickpoint_zone_id', $zone->id)->firstOrFail();

        $actualPickpointAddressIds = $lcOrder->livecargoDeliveries()->distinct()->select('pickpoint_address_id')->get()->pluck('pickpoint_address_id');

        $actualAddresses = PickpointAddress::whereIn('id', $actualPickpointAddressIds)
            ->withCount([
                'livecargoDeliveries' => function (Builder $query) use ($request) {
                    $query->whereDate('created_at', $request->date);
                },
            ])
            ->get();

        return Inertia::render('Curier/AddressList', [
            'zone' => $zone,
            'addresses' => $actualAddresses,
            'date' => now()->format('Y.m.d'),
        ]);
    }

    public function setStatus(Request $request)
    {
        $request->validate([
            'picked_up_status' => [Rule::enum(LivecargoDeliveryPickedUpEnum::class)],
        ]);

        $livecargoDelivery = LivecargoDelivery::where('id', $request->delivery_id)->firstOrFail();

        DB::beginTransaction();

        $livecargoDelivery->picked_up_status = $request->picked_up_status;
        $livecargoDelivery->courier_id = $request->user()->id;
        $livecargoDelivery->status_updated_at = now();
        $livecargoDelivery->save();

        if ($livecargoDelivery->deliveryable instanceof PickUp) {
            $livecargoDelivery->deliveryable->status = $request->picked_up_status == 1 ? PickUpStatus::DONE : PickUpStatus::FAIL_PICKUP;
            $livecargoDelivery->deliveryable->save();
        }

        DB::commit();

        if ($livecargoDelivery->deliveryable instanceof Purchase) {
            (new RecheckPurchaseAction)->handle($livecargoDelivery->deliveryable->task_id);
        }

        return response()->json([
            'status' => 'ok',
        ], 200);
    }
}
