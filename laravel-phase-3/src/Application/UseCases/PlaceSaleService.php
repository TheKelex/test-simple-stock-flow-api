<?php

declare(strict_types=1);

namespace App\Application\UseCases;

use App\Application\Ports\Inbound\PlaceSaleInput;
use App\Application\Ports\Inbound\PlaceSaleOutput;
use App\Application\Ports\Outbound\ProductRepository;
use App\Application\Ports\Outbound\SaleRepository;
use App\Application\Ports\Outbound\UnitOfWork;
use App\Domain\Exceptions\BusinessRuleViolation;
use App\Domain\ValueObjects\Money;

final class PlaceSaleService
{
    public function __construct(
        private ProductRepository $productRepository,
        private SaleRepository $saleRepository,
        private UnitOfWork $unitOfWork,
    ) {
    }

    /**
     * @throws BusinessRuleViolation
     */
    public function execute(PlaceSaleInput $input): PlaceSaleOutput
    {
        return $this->unitOfWork->run(function () use ($input) {
            $productIds = array_map(
                static fn (array $line): string => $line['product_id'],
                $input->lines
            );

            $products = $this->productRepository->findByIds($productIds);
            $productMap = [];

            foreach ($products as $product) {
                $productMap[$product->id()] = $product;
            }

            $saleLines = [];
            $total = 0;

            foreach ($input->lines as $line) {
                $product = $productMap[$line['product_id']] ?? null;

                if ($product === null) {
                    throw new \RuntimeException('Product not found for sale line.');
                }

                $quantity = (int) $line['quantity'];
                $product->reserve($quantity);

                $lineSubtotal = $product->price()->multiply($quantity);
                $saleLines[] = [
                    'product_id' => $product->id(),
                    'quantity' => $quantity,
                    'subtotal' => $lineSubtotal->cents(),
                ];

                $total += $lineSubtotal->cents();
                $this->productRepository->save($product);
            }

            $saleId = 'sale_' . bin2hex(random_bytes(8));
            $salePayload = [
                'id' => $saleId,
                'seller_id' => $input->sellerId,
                'total' => $total,
                'lines' => $saleLines,
            ];

            $this->saleRepository->save($salePayload);

            return new PlaceSaleOutput(
                saleId: $saleId,
                sellerId: $input->sellerId,
                total: $total,
                lines: $saleLines,
            );
        });
    }
}
