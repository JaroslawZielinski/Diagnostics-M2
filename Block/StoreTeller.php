<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use JaroslawZielinski\Diagnostics\Model\Config;
use Magento\Store\Model\Store;

class StoreTeller extends Template
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @inheirtDoc
     */
    public function __construct(
        Config $config,
        StoreManagerInterface $storeManager,
        Context $context,
        array $data = []
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    private function getStore(): Store
    {
        return $this->storeManager->getStore();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getStoreId(): int
    {
        $store = $this->getStore();
        return (int)$store->getId();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getWebsiteId(): int
    {
        $store = $this->getStore();
        return (int)$store->getWebsiteId();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getStoreCode(): string
    {
        $store = $this->getStore();
        return (string)$store->getCode();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getStoreName(): string
    {
        $store = $this->getStore();
        return (string)$store->getName();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function getStoreUrl($fromStore = true): string
    {
        $store = $this->getStore();
        return $store->getCurrentUrl($fromStore);
    }

    /**
     * @throws NoSuchEntityException
     */
    public function isStoreActive(): bool
    {
        $store = $this->getStore();
        return $store->isActive();
    }

    public function getLocale(): ?string
    {
        return $this->config->getStoreLocale();
    }
}
