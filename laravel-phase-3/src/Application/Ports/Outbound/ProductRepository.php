<?php

declare(strict_types=1);

namespace App\Application\Ports\Outbound;

use App\Domain\Entities\Product;

interface ProductRepository
{
    /** @return Product[] */
    public function findByIds(array $ids): array;

    public function save(Product $product): void;
}
