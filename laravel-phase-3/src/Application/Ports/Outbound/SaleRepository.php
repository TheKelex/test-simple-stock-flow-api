<?php

declare(strict_types=1);

namespace App\Application\Ports\Outbound;

interface SaleRepository
{
    /**
     * Persists the sale and its lines as a single aggregate.
     */
    public function save(array $sale): void;
}
