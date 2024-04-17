<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class FaqController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('Faq', ['faqs' => Faq::all()->toArray()]);
    }
}
