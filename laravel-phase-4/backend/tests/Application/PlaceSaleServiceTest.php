<?php

declare(strict_types=1);

namespace Tests\Application;

use App\Application\Ports\Inbound\PlaceSaleInput;
use App\Application\Ports\Outbound\ProductRepository;
use App\Application\Ports\Outbound\UnitOfWork;
use App\Application\UseCases\PlaceSaleService;
use App\Domain\Entities\Product;
use App\Domain\ValueObjects\Money;
use App\Domain\ValueObjects\Quantity;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PlaceSaleServiceTest extends TestCase
{
    #[Test]
    public function it_places_a_sale_and_updates_stock_through_the_unit_of_work(): void
    {
        $product = new Product('p-10', 'Coffee', Money::fromCents(2500), new Quantity(10));

        $repository = new class($product) implements ProductRepository {
            public function __construct(private Product $product)
            {
            }

            public function findByIds(array $ids): array
            {
                return [$this->product];
            }

            public function save(Product $product): void
            {
                $this->product = $product;
            }
        };

        $unitOfWork = new class implements UnitOfWork {
            public function run(callable $operation): mixed
            {
                return $operation();
            }
        };

        $service = new PlaceSaleService($repository, $unitOfWork);
        $result = $service->execute(new PlaceSaleInput('seller-1', [
            ['product_id' => 'p-10', 'quantity' => 2],
        ]));

        $this->assertSame('seller-1', $result->sellerId);
        $this->assertSame(5000, $result->total);
        $this->assertCount(1, $result->lines);
    }
}
