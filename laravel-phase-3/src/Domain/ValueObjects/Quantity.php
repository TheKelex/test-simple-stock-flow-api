<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class Quantity
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function add(self $other): self
    {
        return new self($this->value + $other->value());
    }

    public function subtract(self $other): self
    {
        return new self($this->value - $other->value());
    }
}
