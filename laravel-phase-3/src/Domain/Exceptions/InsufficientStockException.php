<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

final class InsufficientStockException extends BusinessRuleViolation
{
    public function __construct(string $productName, int $requested, int $available)
    {
        parent::__construct(sprintf(
            'Insufficient stock for product "%s". Requested: %d, available: %d.',
            $productName,
            $requested,
            $available
        ));
    }
}
