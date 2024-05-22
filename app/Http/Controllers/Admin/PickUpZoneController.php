<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickpointZones;
use App\Models\WbPickpoints;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class PickUpZoneController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $zones = PickpointZones::all();

        return Inertia::render('Admin/PickUp/Zones', [
            'zones' => $zones,
        ]);
    }

    public function addresses(Request $request, int $zoneId): InertiaResponse
    {
        $zone = PickpointZones::findOrFail($zoneId);
        $addresses = $zone->addresses;

        foreach ($addresses as $address) {
            $address->wb_pickpoint_exist = WbPickpoints::where('address', preg_replace('/\s+/', ' ', trim($address->address)))->exists();
        }

        return Inertia::render('Admin/PickUp/PickPoints', [
            'zone' => $zone,
            'addresses' => $addresses,
        ]);
    }
}
