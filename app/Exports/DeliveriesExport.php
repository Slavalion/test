<?php

namespace App\Exports;

use App\Models\Purchase;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DeliveriesExport implements FromQuery, ShouldAutoSize, WithCustomQuerySize, WithDrawings, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    public function __construct(
        public int $userID,
        public string $status,
        public $fromDate = null,
        public $withoutQRcode = false
    ) {
    }

    public function querySize(): int
    {
        return 100;
    }

    public function headings(): array
    {
        return [
            'Код задачи',
            'Название',
            'Артикул',
            'QR код',
            'Телефон',
            'Код получения',
            'Адрес',
            'Дата создания',
            'Дата выкупа',
            'Прибыл в пункт выдачи',
            'Чек',
            'Цена',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getDefaultRowDimension()->setRowHeight(40);
        // $sheet->getDelegate()->getRowDimension('2')->setRowHeight(40)();
    }

    public function drawings()
    {
        if ($this->withoutQRcode) {
            return [];
        }

        // $drawing = new Drawing();
        // $drawing->setName('Logo');
        // $drawing->setDescription('This is my logo');
        // $drawing->setPath(storage_path('app/public/qrcodes/71722d636f64652d35.png'));
        // $drawing->setHeight(50);
        // $drawing->setCoordinates('D1');

        $purchases = $this->query()->get();
        $drawings = [];
        foreach ($purchases as $key => $purchase) {
            if (! Storage::exists('public/'.$purchase->delivery_qrcode)) {
                continue;
            }

            try {
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('This is my logo');
                $drawing->setPath(storage_path('app/public/'.$purchase->delivery_qrcode));
                $drawing->setHeight(50);
                $drawing->setCoordinates('D'.($key + 2));
                $drawings[] = ($drawing);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        return $drawings;
    }

    /**
     * @var Purchase
     */
    public function map($purchase): array
    {
        // dd(storage_path('app/public/'.$purchase->delivery_qrcode));
        if ($purchase->delivery_qrcode == '') {
            $drawing = '';
        } else {
            // $drawing = new Drawing();
            // $drawing->setName('Logo');
            // $drawing->setDescription('This is my logo');
            // $drawing->setPath(storage_path('app/public/'.$purchase->delivery_qrcode));
            // $drawing->setHeight(50);
            // $drawing->setCoordinates('C5');
        }

        $price = null;

        // if ($purchase->walletTransaction) {
        //     $price = $purchase->walletTransaction->amount;
        // } elseif ($purchase->telegramTransaction) {
        //     $price = $purchase->telegramTransaction->data['bot_data']['amount'] ?? null;
        // }

        return [
            // $purchase->id,
            $purchase->task_id,
            $purchase?->product?->name,
            $purchase->product_id,
            '',
            $purchase->delivery_phone,
            $purchase->delivery_pin,
            $purchase->address,
            $purchase->created_ts,
            $purchase->purchase_ts,
            $purchase->ready_to_pick_up_at,
            $purchase->receipt ?? '',
            $purchase->price != 0 ? $purchase->price / 100 : '',
        ];
    }

    public function query()
    {
        $purchasesQuery = Purchase::query()->where('user_id', $this->userID)
            ->with([
                // 'walletTransaction',
                // 'telegramTransaction',
                'product',
            ]);

        if ($this->status == 'all') {
            $purchasesQuery->whereNotIn('delivery_status', [
                Purchase::DELIVERY_STATUS_NONE,
                // Purchase::DELIVERY_STATUS_PICKED_UP,
            ]);
        } else {
            $purchasesQuery->where('delivery_status', $this->status);
        }

        if ($this->fromDate) {
            $purchasesQuery->whereDate('created_at', '>=', $this->fromDate);
        }

        return $purchasesQuery->with('product');
    }
}
