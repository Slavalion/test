<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class DevController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Admin/Dev', []);
    }

    public function getByTaskID(Request $request): JsonResponse
    {
        $purchase = Purchase::where('task_id', $request->task_id)->firstOrFail();

        return response()->json($purchase);
    }
}
