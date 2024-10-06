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

    /**
     */
    public function getEntries(): array
    {
        return [
            ['type' => 'text', 'label' => __('store_id'), 'value' => $this->getStoreId()],
            ['type' => 'text', 'label' => __('store_code'), 'value' => $this->getStoreCode()],
            ['type' => 'text', 'label' => __('website_id'), 'value' => $this->getWebsiteId()],
            ['type' => 'text', 'label' => __('store_name'), 'value' => $this->getStoreName()],
            [
                'type' => 'link',
                'label' => __('store_url'),
                'value' => $this->getStoreUrl(),
                'options' => [
                    'class' => 'link',
                    'title' => $this->getStoreName(),
                    'target' => '_blank',
                    'href' => $this->getStoreUrl(),
                ]
            ],
            ['type' => 'text', 'label' => __('is_store_active'), 'value' => $this->isStoreActive()],
            ['type' => 'text', 'label' => __('locale'), 'value' => $this->getLocale()]
        ];
    }

    /**
     */
    private function getStore(): Store
    {
        return $this->storeManager->getStore();
    }

    /**
     * @throws NoSuchEntityException
     */
    protected function getStoreId(): int
    {
        $store = $this->getStore();
        return (int)$store->getId();
    }

    /**
     * @throws NoSuchEntityException
     */
    protected function getWebsiteId(): int
    {
        $store = $this->getStore();
        return (int)$store->getWebsiteId();
    }

    /**
     * @throws NoSuchEntityException
     */
    protected function getStoreCode(): string
    {
        $store = $this->getStore();
        return (string)$store->getCode();
    }

    /**
     * @throws NoSuchEntityException
     */
    protected function getStoreName(): string
    {
        $store = $this->getStore();
        return (string)$store->getName();
    }

    /**
     * @throws NoSuchEntityException
     */
    protected function getStoreUrl($fromStore = true): string
    {
        $store = $this->getStore();
        return $store->getCurrentUrl($fromStore);
    }

    /**
     * @throws NoSuchEntityException
     */
    protected function isStoreActive(): bool
    {
        $store = $this->getStore();
        return $store->isActive();
    }

    /**
     */
    protected function getLocale(): ?string
    {
        return $this->config->getStoreLocale();
    }
}
