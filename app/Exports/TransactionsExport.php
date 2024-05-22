<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(
        private int $userID,
    ) {
    }

    public function headings(): array
    {
        return [
            'ID транзакции',
            'Сумма',
            'Назначение',
            'Тип',
            'Дата',
        ];
    }

    /**
     * @var Transaction
     */
    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->amount / 100,
            $transaction->target->toTitle(),
            $transaction->type->toTitle(),
            $transaction->created_at,
        ];
    }

    public function query()
    {
        $purchasesQuery = Transaction::query()->where('user_id', $this->userID);

        return $purchasesQuery;
    }
}
