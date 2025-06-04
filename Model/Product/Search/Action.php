<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Product\Search;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Psr\Log\LoggerInterface;

class Action
{
    /**
     * @var Grouped
     */
    private $grouped;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     */
    public function __construct(
        Grouped $grouped,
        LoggerInterface $logger
    ) {
        $this->grouped = $grouped;
        $this->logger = $logger;
    }

    public function execute(Product $product, int $assocCount): bool
    {
        $productSKU = $product->getSku();
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection $collection */
        $collection = $this->getAssociatedProducts($product);
        $count = count($collection->getItems());
        if ($assocCount === (int)$count) {
            $this->logger->info(sprintf('Found sku: \'%s\'', $productSKU));
            return true;
        }
        return false;
    }

    /**
     */
    public function getAssociatedProducts(Product $product): Collection
    {
        return $this->grouped
            ->getAssociatedProductCollection($product)
            ->addAttributeToSelect('*')
            ->setOrder('position', 'ASC');
    }
}
