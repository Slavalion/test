<?php

namespace App\Exports;

use App\Models\WalletTransaction;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WalletTransactionsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(
        public int $userID,
    ) {
    }

    public function headings(): array
    {
        return [
            'Кошелек',
            'Сумма',
            'TID Выкупа',
            'Артикул',
            'Название товара',
            'Дата',
        ];
    }

    /**
     * @var WalletTransaction
     */
    public function map($walletTransaction): array
    {
        return [
            $walletTransaction->wallet_name,
            $walletTransaction->amount,
            $walletTransaction->purchase->task_id,
            $walletTransaction->purchase->product->remote_id,
            $walletTransaction->purchase->product->name,
            $walletTransaction->created_at,
        ];
    }

    public function query()
    {
        $purchasesQuery = WalletTransaction::query()->where('user_id', $this->userID);

        return $purchasesQuery->with(['purchase', 'purchase.product']);
    }
}
