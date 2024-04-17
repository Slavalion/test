<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionTarget;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class StatisticController extends Controller
{
    public function index(): InertiaResponse
    {

        return Inertia::render('Admin/Statistic', [
            'purchases' => [
                'transactions' => Transaction::where('target', TransactionTarget::PURCHASE)->whereDate('created_at', now())->count(),
                'returns' => Transaction::where('target', TransactionTarget::RETURN_PURCHASE)->whereDate('created_at', now())->count(),
                'total_sum' => Transaction::where('target', TransactionTarget::PURCHASE)->whereDate('created_at', now())->sum('amount'),
                'return_sum' => Transaction::where('target', TransactionTarget::RETURN_PURCHASE)->whereDate('created_at', now())->sum('amount'),
            ],
            'reviews' => [
                'transactions' => Transaction::where('target', TransactionTarget::REVIEW)->whereDate('created_at', now())->count(),
                'returns' => Transaction::where('target', TransactionTarget::RETURN_REVIEW)->whereDate('created_at', now())->count(),
                'total_sum' => Transaction::where('target', TransactionTarget::REVIEW)->whereDate('created_at', now())->sum('amount'),
                'return_sum' => Transaction::where('target', TransactionTarget::RETURN_REVIEW)->whereDate('created_at', now())->sum('amount'),
            ],
        ]);
    }
}
