<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchasesExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(
        public int $userID,
        public string $status
    ) {
    }

    public function headings(): array
    {
        return [
            'ID',
            'Task ID',
            'Артикул',
            'Название',
            'Количество',
            'Адрес',
            'Адрес',
            'Цена',
            'Дата выкупа',
            'Статус',
            'Статус доставки',
        ];
    }

    /**
     * @var Purchase
     */
    public function map($purchase): array
    {
        return [
            $purchase->id,
            $purchase->task_id,
            $purchase->product->remote_id,
            $purchase->product->name,
            $purchase->quantity,
            $purchase->address,
            $purchase->product->price / 100,
            $purchase->created_ts,
            $purchase->purchase_ts,
            $purchase->status,
            $purchase->delivery_status,
        ];
    }

    public function query()
    {
        $purchasesQuery = Purchase::query()->where('user_id', $this->userID);

        if ($this->status == 'all') {
            // $purchasesQuery->whereNotIn('status', [
            //     Purchase::STATUS_DONE,
            //     // Purchase::DELIVERY_STATUS_PICKED_UP,
            // ]);
        } else {
            $purchasesQuery->where('status', $this->status);
        }

        return $purchasesQuery->with('product');
    }
}
