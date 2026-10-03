<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\Ports\Inbound\PlaceSaleInput;
use App\Application\Ports\Inbound\PlaceSaleOutput;
use App\Application\Ports\Outbound\ProductRepository;
use App\Application\Ports\Outbound\UnitOfWork;
use App\Domain\Entities\Product;

final class PlaceSaleService
{
    public function __construct(
        private ProductRepository $productRepository,
        private UnitOfWork $unitOfWork,
    ) {
    }

    public function execute(PlaceSaleInput $input): PlaceSaleOutput
    {
        return $this->unitOfWork->run(function () use ($input) {
            $productIds = array_map(
                static fn (array $line): string => $line['product_id'],
                $input->lines,
            );

            $products = $this->productRepository->findByIds($productIds);
            $productMap = [];

            foreach ($products as $product) {
                $productMap[$product->id()] = $product;
            }

            $lines = [];
            $total = 0;

            foreach ($input->lines as $line) {
                $product = $productMap[$line['product_id']] ?? null;

                if ($product === null) {
                    throw new \RuntimeException('Product not found for sale line.');
                }

                $quantity = (int) $line['quantity'];
                $product->reserve($quantity);

                $subtotal = $product->price()->multiply($quantity)->cents();
                $lines[] = [
                    'product_id' => $product->id(),
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];

                $total += $subtotal;
                $this->productRepository->save($product);
            }

            $saleId = 'sale_' . bin2hex(random_bytes(4));

            return new PlaceSaleOutput(
                saleId: $saleId,
                sellerId: $input->sellerId,
                total: $total,
                lines: $lines,
            );
        });
    }
}
