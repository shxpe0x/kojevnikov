<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

final readonly class Money
{
    public function __construct(
        public int $amount,
        public string $currency = 'RUB'
    ) {
        if ($amount < 0) {
            throw new InvalidArgumentException('Amount cannot be negative');
        }

        if (strlen($currency) !== 3) {
            throw new InvalidArgumentException('Currency must be 3 characters');
        }
    }

    /**
     * Create from float value.
     */
    public static function fromFloat(float $value, string $currency = 'RUB'): self
    {
        return new self((int) round($value * 100), $currency);
    }

    /**
     * Get float representation.
     */
    public function toFloat(): float
    {
        return $this->amount / 100;
    }

    /**
     * Format as string.
     */
    public function format(): string
    {
        return number_format($this->toFloat(), 2) . ' ' . $this->currency;
    }

    /**
     * Add money.
     */
    public function add(self $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Cannot add different currencies');
        }

        return new self($this->amount + $other->amount, $this->currency);
    }

    /**
     * Subtract money.
     */
    public function subtract(self $other): self
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Cannot subtract different currencies');
        }

        return new self($this->amount - $other->amount, $this->currency);
    }
}
