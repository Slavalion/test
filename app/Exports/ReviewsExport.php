<?php

namespace App\Exports;

use App\Models\Review;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReviewsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
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
            'Рейтинг',
            'Отзыв',
            'Статус',
            'Дата отзыва',
        ];
    }

    /**
     * @var Review
     */
    public function map($review): array
    {
        return [
            $review->id,
            $review->task_id,
            $review->purchase->product->remote_id,
            $review->purchase->product->name,
            $review->rate,
            $review->review,
            match ($review->status) {
                'processing' => 'В работе',
                'failed' => 'Ошибка',
                'done' => 'Завершен',
                default => 'не определен'
            },
            $review->public_ts,
        ];
    }

    public function query()
    {
        $reviewQuery = Review::query()->where('user_id', $this->userID);

        if ($this->status != 'all') {
            $reviewQuery->where('status', $this->status);
        }

        return $reviewQuery->with(['purchase.product']);
    }
}
