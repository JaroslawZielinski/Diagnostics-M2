<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Console\OrderProcessing;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Psr\Log\LoggerInterface;


class AverageTime
{
    /**
     * @var OrderCollectionFactory
     */
    private $orderCollectionFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     */
    public function __construct(
        OrderCollectionFactory $orderCollectionFactory,
        LoggerInterface $logger
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->logger = $logger;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute(
        callable $start = null,
        callable $iteration = null,
        callable $end = null
    ): float {
        $orderCollection = $this->orderCollectionFactory->create();
        $max = $orderCollection->getSize();
        if (!empty($start)) {
            $start($max);
        }
        $totalProcessingTime = 0;
        foreach ($orderCollection as $order) {
            try {
                $createdAt = new \DateTime($order->getCreatedAt());
                $completedAt = new \DateTime($order->getUpdatedAt());
                $processingTime = $completedAt->diff($createdAt)->days;
                $totalProcessingTime += $processingTime;
                if (!empty($iteration)) {
                    $iteration();
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }
        if (!empty($end)) {
            $end();
        }
        $averageProcessingTime = $totalProcessingTime / $max;
        return $averageProcessingTime;
    }
}
