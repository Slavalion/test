<?php

namespace App\Exports\Admin;

use App\Models\LivecargoDelivery;
use App\Models\PickUp;
use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CourierExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(
        public int $courierID,
    ) {
    }

    public function headings(): array
    {
        return [
            'ID',
            'Наименование',
            'Артикул',
            'Тип',
            'Статус',
            'Время отметки',
            'Дата',
        ];
    }

    /**
     * @var LivecargoDelivery
     */
    public function map($pickUp): array
    {
        return [
            $pickUp->id,
            $pickUp->deliveryable->product->name,
            $pickUp->deliveryable->product->remote_id,
            match ($pickUp->deliveryable_type) {
                Purchase::class => 'Автовыкуп',
                PickUp::class => 'Загруженный',
                default => 'Не определено'
            },
            $pickUp->picked_up_status->toTitle(),
            $pickUp->status_updated_at,
            $pickUp->created_at->toDateString(),
        ];
    }

    public function query()
    {
        $livecargoDelivery = LivecargoDelivery::query()->where('courier_id', $this->courierID)
            ->whereDate('created_at', now())
            ->with('deliveryable.product');

        return $livecargoDelivery;
    }
}
