<?php

declare(strict_types=1);

namespace App\Application\Ports\Inbound;

final class PlaceSaleOutput
{
    /**
     * @param array<int, array{product_id: string, quantity: int, subtotal: int}> $lines
     */
    public function __construct(
        public readonly string $saleId,
        public readonly string $sellerId,
        public readonly int $total,
        public readonly array $lines,
    ) {
    }
}
