<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActionItemStatus;
use App\Enums\ReviewComplaintStatus;
use App\Enums\ReviewReactionStatus;
use App\Http\Controllers\Controller;
use App\Models\ActionCart;
use App\Models\Purchase;
use App\Models\Review;
use App\Models\ReviewComplaint;
use App\Models\ReviewReaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class DashboardController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        return Inertia::render('Admin/Dashboard', [
            'totals' => [
                'purchases' => Purchase::where('status', Purchase::STATUS_PROCESSING)->count(),
                'reviews' => Review::where('status', 'processing')->count(),
                'reviewReactions' => ReviewReaction::where('status', ReviewReactionStatus::PROCESS)->count(),
                'reviewComplaints' => ReviewComplaint::where('status', ReviewComplaintStatus::PROCESS)->count(),
                'cartActions' => ActionCart::where('status', ActionItemStatus::PROCESS)->count(),
            ],
            'missedPurchases' => Purchase::select(['id', 'task_id', 'product_id', 'address', 'purchase_at', 'status', 'delivery_status', 'created_at', 'updated_at'])
                ->where('status', Purchase::STATUS_PROCESSING)
                ->whereDate('purchase_at', '<', now()->toDateString())
                ->get(),
        ]);
    }
}
