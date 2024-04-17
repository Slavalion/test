<?php

namespace App\Enums;

enum ReviewReactionType: int
{
    case LIKE = 1;
    case DISLIKE = 2;

    public static function fromString(string $type): static
    {
        return match (true) {
            $type == 'like' => self::LIKE,
            $type == 'dislike' => self::DISLIKE,
            default => self::LIKE,
        };
    }

    public function toString(): string
    {
        return match ($this) {
            ReviewReactionType::LIKE => 'like',
            ReviewReactionType::DISLIKE => 'dislike',
        };
    }
}
