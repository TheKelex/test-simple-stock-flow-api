<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\Ports\Outbound\ProductRepository;

final class ProductCatalogService
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * @return array<int, array{id: string, name: string, price_cents: int, stock: int}>
     */
    public function listActiveProducts(): array
    {
        $products = $this->productRepository->findByIds([]);

        return array_map(
            static fn ($product) => [
                'id' => $product->id(),
                'name' => $product->name(),
                'price_cents' => $product->price()->cents(),
                'stock' => $product->stock()->value(),
            ],
            $products
        );
    }
}
