<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickpointZones;
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

        return Inertia::render('Admin/PickUp/PickPoints', [
            'zone' => $zone,
            'addresses' => $addresses,
        ]);
    }
}
