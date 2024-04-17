<?php

namespace App\Http\Controllers;

use App\Enums\LivecargoDeliveryPickedUpEnum;
use App\Enums\PickUpStatus;
use App\Models\LivecargoDelivery;
use App\Models\LivecargoOrder;
use App\Models\PickpointAddress;
use App\Models\PickpointZones;
use App\Models\PickUp;
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
        $zone = PickpointZones::findOrFail($request->zone);
        $lcOrder = LivecargoOrder::whereDate('created_at', $request->date)->where('pickpoint_zone_id', $zone->id)->firstOrFail();
        $deliveries = $lcOrder->livecargoDeliveries()->where('pickpoint_address_id', $request->address)->with(['deliveryable', 'deliveryable.product'])->get();

        // $purchases = collect();
        // foreach ($deliveries as $delivery) {
        //     $purchases->push($delivery->purchase);
        // }

        $pickpointAddresses = PickpointAddress::where('pickpoint_zone_id', $zone->id)->get();

        $sortedAddress = [];

        foreach ($pickpointAddresses as $pAddress) {
            $sortedAddress[$pAddress->address] = [];
        }

        foreach ($deliveries as $delivery) {
            if (! $delivery->deliveryable) {
                continue;
            }
            if (! isset($sortedAddress[$delivery->deliveryable->address])) {
                info('Что-то не так с адресом '.$delivery->deliveryable->address);
            }

            $delivery->version = now()->format('Ymd');

            $sortedAddress[$delivery->deliveryable->address][] = $delivery;
        }

        foreach ($sortedAddress as $key => $sAddr) {
            if (count($sAddr) == 0) {
                unset($sortedAddress[$key]);
            }
        }

        return Inertia::render('Curier/Deliveries', [
            'zone' => $zone,
            // 'deliveries' => $deliveries,
            // 'purchases' => $purchases,
            'groups' => $sortedAddress,
        ]);
    }

    public function zoneList(Request $request)
    {
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
            $livecargoDelivery->deliveryable->status = PickUpStatus::DONE;
            $livecargoDelivery->deliveryable->save();
        }

        DB::commit();

        return response()->json([
            'status' => 'ok',
        ], 200);
    }
}
