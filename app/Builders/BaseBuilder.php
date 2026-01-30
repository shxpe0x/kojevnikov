<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseBuilder extends Builder
{
    /**
     * Filter by active status.
     */
    public function active(): self
    {
        return $this->where('status', 'active');
    }

    /**
     * Filter by date range.
     */
    public function dateRange(string $column, string $from, string $to): self
    {
        return $this->whereBetween($column, [$from, $to]);
    }

    /**
     * Search in multiple columns.
     */
    public function search(array $columns, string $term): self
    {
        return $this->where(function (Builder $query) use ($columns, $term) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', "%{$term}%");
            }
        });
    }
}
