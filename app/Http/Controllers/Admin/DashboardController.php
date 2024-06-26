<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActionItemStatus;
use App\Enums\ReviewComplaintStatus;
use App\Enums\ReviewReactionStatus;
use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\ActionCart;
use App\Models\Purchase;
use App\Models\Review;
use App\Models\ReviewComplaint;
use App\Models\ReviewReaction;
use App\Models\Transaction;
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
            'missedReviews' => Review::where('status', 'processing')
                ->whereDate('public_at', '<', now())
                ->orderByDesc('created_at')
                ->get(),
            'recentTopUps' => Transaction::whereDate('created_at', '>=', now()->subDays(7))
                ->whereIn('target', [TransactionTarget::BALANCE_MANUALLY, TransactionTarget::TINKOFF])
                ->where('type', TransactionType::TOP_UP)
                ->orderByDesc('created_at')
                ->get(),
            'tinkoffTotal' => Transaction::whereDate('created_at', '>=', now()->subDays(7))
                ->where('target', TransactionTarget::TINKOFF)
                ->where('type', TransactionType::TOP_UP)
                ->orderByDesc('created_at')
                ->sum('amount'),
            'manualTotal' => Transaction::whereDate('created_at', '>=', now()->subDays(7))
                ->where('target', TransactionTarget::BALANCE_MANUALLY)
                ->where('type', TransactionType::TOP_UP)
                ->orderByDesc('created_at')
                ->sum('amount'),
        ]);
    }
}
