<?php

declare(strict_types=1);

namespace App\Application\Ports\Outbound;

interface UnitOfWork
{
    public function run(callable $operation): mixed;
}
