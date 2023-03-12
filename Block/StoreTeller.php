<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class StoreTeller extends Template
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @inheirtDoc
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Context $context,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getStoreId(): int
    {
        return (int)$this->storeManager->getStore()->getId();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getWebsiteId(): int
    {
        return (int)$this->storeManager->getStore()->getWebsiteId();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getStoreCode(): string
    {
        return (string)$this->storeManager->getStore()->getCode();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getStoreName(): string
    {
        return (string)$this->storeManager->getStore()->getName();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getStoreUrl($fromStore = true): string
    {
        return $this->storeManager->getStore()->getCurrentUrl($fromStore);
    }

    /**
     * @throws NoSuchEntityException
     */
    public function isStoreActive(): bool
    {
        return $this->storeManager->getStore()->isActive();
    }
}
