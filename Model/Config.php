<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const PATH_SETTINGS_STORE_TELLER_ENABLE = 'jaroslawzielinski_diagnostics/settings/store_teller_enable';

    public const PATH_PROGRESSBAR_FORMAT = 'jaroslawzielinski_diagnostics/progress_bar/format';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isStoreTellerEnable(): bool
    {
        return $this->scopeConfig
            ->isSetFlag(self::PATH_SETTINGS_STORE_TELLER_ENABLE, ScopeInterface::SCOPE_STORE);
    }

    public function getProgressBarFormat(): ?string
    {
        $progressBarFormat = $this->scopeConfig
            ->getValue(self::PATH_PROGRESSBAR_FORMAT);
        return empty($progressBarFormat) ? null : (string)$progressBarFormat;
    }
}
