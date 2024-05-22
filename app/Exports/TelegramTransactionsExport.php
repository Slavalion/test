<?php

namespace App\Exports;

use App\Models\TelegramTransaction;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TelegramTransactionsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(
        private int $userID,
    ) {
    }

    public function headings(): array
    {
        return [
            'Сумма',
            'TID Выкупа',
            'Артикул',
            'Название товара',
            'Дата',
        ];
    }

    /**
     * @var TelegramTransaction
     */
    public function map($telegramTransaction): array
    {
        return [
            $telegramTransaction->data['bot_data']['amount'],
            $telegramTransaction->purchase->task_id,
            $telegramTransaction->purchase->product->remote_id,
            $telegramTransaction->purchase->product->name,
            $telegramTransaction->created_at,
        ];
    }

    public function query()
    {
        $purchasesQuery = TelegramTransaction::query()->where('user_id', $this->userID);

        return $purchasesQuery->with(['purchase', 'purchase.product']);
    }
}
