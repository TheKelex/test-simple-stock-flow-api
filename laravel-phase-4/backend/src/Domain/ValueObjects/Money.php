<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class Money
{
    public function __construct(private int $cents)
    {
        if ($cents < 0) {
            throw new \InvalidArgumentException('Money cannot be negative.');
        }
    }

    public static function fromCents(int $cents): self
    {
        return new self($cents);
    }

    public function cents(): int
    {
        return $this->cents;
    }

    public function multiply(int $quantity): self
    {
        return new self($this->cents * $quantity);
    }
}
