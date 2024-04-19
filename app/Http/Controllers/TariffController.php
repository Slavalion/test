<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class TariffController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('Tariffs', ['tariffs' => Tariff::all()->toArray()]);
    }
}
