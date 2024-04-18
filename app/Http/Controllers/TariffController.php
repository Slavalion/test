<?php

namespace App\Http\Controllers;

use App\Models\Tarrif;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class TariffController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('Tarrifs', ['tarrifs' => Tarrif::all()->toArray()]);
    }
}
