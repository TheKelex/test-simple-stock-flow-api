<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Product;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\Quantity;

final class ProductMapper
{
    public function toDomain(array $data): Product
    {
        return new Product(
            id: $data['id'],
            name: $data['name'],
            price: Money::fromCents((int) $data['price_cents']),
            stock: new Quantity((int) $data['stock']),
            deleted: (bool) ($data['deleted_at'] ?? false),
        );
    }

    public function toRow(Product $product): array
    {
        return [
            'id' => $product->id(),
            'name' => $product->name(),
            'price_cents' => $product->price()->cents(),
            'stock' => $product->stock()->value(),
            'updated_at' => now(),
        ];
    }
}
