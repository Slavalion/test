<?php

namespace App\Http\Controllers;

use App\Exports\WalletTransactionsExport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WalletTransactionController extends Controller
{
    public function index(Request $request): InertiaResponse
    {

        if ($request->section == 'balance') {
            $activeSection = 'balance';
            $transactionsQuery = $request->user()->transactions();
        } elseif ($request->section == 'telegram') {
            $activeSection = 'telegram';
            $transactionsQuery = $request->user()->tgTransactions()->with(['purchase', 'purchase.product']);
        } elseif ($request->section == 'refill') {
            $activeSection = 'refill';
            $transactionsQuery = $request->user()->transactions()->whereIn('type', [1]);
        }elseif ($request->section == 'debit') {
            $activeSection = 'debit';
            $transactionsQuery = $request->user()->transactions()->whereIn('type', [-1]);
        } else {
            $activeSection = 'wallets';
            $transactionsQuery = $request->user()->walletTransactions()->with(['purchase', 'purchase.product']);
        }

        $transactionsPaginator = $transactionsQuery->orderByDesc('created_at')->paginate(10)->withQueryString();

        return Inertia::render('Transactions', [
            'transactions' => $transactionsPaginator->items(),
            'paginator' => $transactionsPaginator->toArray()['links'],
            'activeSection' => $activeSection,
        ]);
    }

    public function download(Request $request): BinaryFileResponse
    {
        return (new WalletTransactionsExport($request->user()->id))->download('wallet-transactions.xlsx');
    }
}
