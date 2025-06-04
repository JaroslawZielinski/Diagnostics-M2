<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use JaroslawZielinski\Diagnostics\Model\Product\Search\Action;
use Psr\Log\LoggerInterface;

class Find
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Action
     */
    private $action;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        StoreManagerInterface $storeManager,
        CollectionFactory $productCollectionFactory,
        ProductRepositoryInterface $productRepository,
        Action $action,
        LoggerInterface $logger
    ) {
        $this->storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
        $this->action = $action;
        $this->logger = $logger;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute(
        int $websiteId,
        int $storeId,
        int $assocCount,
        callable $start = null,
        callable $iteration = null,
        callable $end = null
    ): int {
        $store = $this->storeManager->getStore($storeId);
        $realWebsiteId = (int)$store->getWebsiteId();
        if ($realWebsiteId !== $websiteId) {
            throw new LocalizedException(__('Website Id is wrong.'));
        }
        $productCollection = $this->getProductCollection($storeId);
        $max = $productCollection->getSize();
        if (!empty($start)) {
            $start($max);
        }
        $found = 0;
        foreach ($productCollection as $productItem) {
            try {
                /** @var Product $product */
                $product = $this->productRepository->getById((int)$productItem->getProductId());
                if (!empty($iteration)) {
                    $iteration();
                }
                $isFound = $this->action->execute($product, $assocCount);
                if ($isFound) {
                    $found++;
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }
        if (!empty($end)) {
            $end();
        }
        return $found;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    private function getProductCollection(int $storeId): Collection
    {
        $rootCategoryId = $this->storeManager->getStore($storeId)->getRootCategoryId();
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*');
        $productCollection->getSelect()
            ->joinInner(
                ['cp' => $productCollection->getTable('catalog_category_product')],
                'e.entity_id = cp.product_id'
            )
            ->joinInner(
                ['c' => $productCollection->getTable('catalog_category_entity')],
                'cp.category_id = c.entity_id',
                ['c.path']
            )
            ->where('c.path LIKE ?', '1/' . $rootCategoryId . '/%');
        $productCollection->setStoreId($storeId);
        return $productCollection;
    }
}
