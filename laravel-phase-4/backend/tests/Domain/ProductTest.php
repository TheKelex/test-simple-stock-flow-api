<?php

declare(strict_types=1);

namespace Tests\Domain;

use App\Domain\Entities\Product;
use App\Domain\Exceptions\InsufficientStockException;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\Quantity;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    #[Test]
    public function it_reduces_stock_when_reserving_quantity(): void
    {
        $product = new Product('p-1', 'Coffee', Money::fromCents(2500), new Quantity(10));

        $product->reserve(3);

        $this->assertSame(7, $product->stock()->value());
    }

    #[Test]
    public function it_rejects_sale_when_stock_is_insufficient(): void
    {
        $this->expectException(InsufficientStockException::class);

        $product = new Product('p-2', 'Tea', Money::fromCents(1500), new Quantity(2));
        $product->reserve(3);
    }
}
