<?php

namespace App\Http\Controllers;

use App\Exports\TelegramTransactionsExport;
use App\Exports\TransactionsExport;
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
        $excelExporter = match ($request->section) {
            'balance' => TransactionsExport::class,
            'telegram' => TelegramTransactionsExport::class,
            default => WalletTransactionsExport::class,
        };

        $fileName = match ($request->section) {
            'balance' => 'balance-transactions.xlsx',
            'telegram' => 'telegram-transactions.xlsx',
            default => 'wallet-transactions.xlsx',
        };

        return (new $excelExporter($request->user()->id))->download($fileName);
    }
}
