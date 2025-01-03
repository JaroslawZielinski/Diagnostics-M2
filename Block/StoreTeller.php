<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use JaroslawZielinski\Diagnostics\Model\Config;
use Magento\Store\Model\Store;

class StoreTeller extends Template
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @inheirtDoc
     */
    public function __construct(
        Config $config,
        Context $context,
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     */
    public function getEntries(): array
    {
        $storeName = $this->getStoreName();
        $storeUrl = $this->getStoreUrl();
        return [
            ['type' => 'text', 'label' => __('store_id'), 'value' => $this->getStoreId()],
            ['type' => 'text', 'label' => __('store_code'), 'value' => $this->getStoreCode()],
            ['type' => 'text', 'label' => __('website_id'), 'value' => $this->getWebsiteId()],
            ['type' => 'text', 'label' => __('store_name'), 'value' => $storeName],
            [
                'type' => 'link',
                'label' => __('store_url'),
                'value' => $storeUrl,
                'options' => [
                    'class' => 'link',
                    'title' => $storeName,
                    'target' => '_blank',
                    'href' => $storeUrl,
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
        return $this->_storeManager->getStore();
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

    /**
     */
    public function getPopupMinWidth(): ?string
    {
        return $this->config->getStoreTellerPopupMinWidth();
    }

    /**
     */
    public function getButtonColor(): ?string
    {
        return $this->config->getStoreTellerButtonColor();
    }

    /**
     */
    public function getButtonBackground(): ?string
    {
        return $this->config->getStoreTellerButtonBackground();
    }

    /**
     */
    public function getPopupColor(): ?string
    {
        return  $this->config->getStoreTellerPopupColor();
    }

    /**
     */
    public function getPopupBackground(): ?string
    {
        return $this->config->getStoreTellerPopupBackground();
    }
}
