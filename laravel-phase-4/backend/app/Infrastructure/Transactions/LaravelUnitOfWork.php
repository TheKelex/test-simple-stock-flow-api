<?php

declare(strict_types=1);

namespace App\Infrastructure\Transactions;

use App\Application\Ports\Outbound\UnitOfWork;
use Illuminate\Support\Facades\DB;

final class LaravelUnitOfWork implements UnitOfWork
{
    public function run(callable $operation): mixed
    {
        return DB::transaction($operation);
    }
}
