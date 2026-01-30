<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Example Enum for type-safe status values
 * Enums provide compile-time checking for constant values
 */
enum StatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';

    /**
     * Get human-readable label
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => 'В ожидании',
            self::PROCESSING => 'В обработке',
            self::COMPLETED => 'Завершено',
            self::FAILED => 'Ошибка',
            self::CANCELLED => 'Отменено',
        };
    }

    /**
     * Get color for UI
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'gray',
            self::PROCESSING => 'blue',
            self::COMPLETED => 'green',
            self::FAILED => 'red',
            self::CANCELLED => 'orange',
        };
    }

    /**
     * Check if status is terminal (cannot transition further)
     */
    public function isTerminal(): bool
    {
        return in_array($this, [self::COMPLETED, self::FAILED, self::CANCELLED], true);
    }
}
