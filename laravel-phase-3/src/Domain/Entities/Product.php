<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Exceptions\InsufficientStockException;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\Quantity;

final class Product
{
    public function __construct(
        private string $id,
        private string $name,
        private Money $price,
        private Quantity $stock,
        private bool $deleted = false,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function stock(): Quantity
    {
        return $this->stock;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function reserve(int $quantityToReserve): void
    {
        $requested = new Quantity($quantityToReserve);

        if ($this->stock->value() < $requested->value()) {
            throw new InsufficientStockException($this->name, $requested->value(), $this->stock->value());
        }

        $this->stock = $this->stock->subtract($requested);
    }
}
