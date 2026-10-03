<?php

declare(strict_types=1);

namespace App\Application\Ports\Inbound;

final class PlaceSaleInput
{
    /**
     * @param array<int, array{product_id: string, quantity: int}> $lines
     */
    public function __construct(
        public readonly string $sellerId,
        public readonly array $lines,
    ) {
    }
}
