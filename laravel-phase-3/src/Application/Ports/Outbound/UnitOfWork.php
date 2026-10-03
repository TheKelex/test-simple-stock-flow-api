<?php

declare(strict_types=1);

namespace App\Application\Ports\Outbound;

interface UnitOfWork
{
    /**
     * Executes the provided operation atomically, without exposing persistence details to the use case.
     */
    public function run(callable $operation): mixed;
}
