<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Application\Ports\Outbound\ProductRepository;
use App\Domain\Entities\Product;
use Illuminate\Support\Facades\DB;

final class EloquentProductRepository implements ProductRepository
{
    public function __construct(
        private ProductMapper $mapper,
    ) {
    }

    public function findByIds(array $ids): array
    {
        if ($ids === []) {
            $rows = DB::table('products')->whereNull('deleted_at')->get()->all();
            return array_map(fn (array $row) => $this->mapper->toDomain((array) $row), $rows);
        }

        $rows = DB::table('products')->whereIn('id', $ids)->whereNull('deleted_at')->get()->all();

        return array_map(fn (array $row) => $this->mapper->toDomain((array) $row), $rows);
    }

    public function save(Product $product): void
    {
        DB::table('products')->updateOrInsert(
            ['id' => $product->id()],
            $this->mapper->toRow($product),
        );
    }
}
