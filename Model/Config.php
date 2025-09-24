<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use JaroslawZielinski\Diagnostics\Helper\Data;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class Config
{
    public const PATH_SETTINGS_STORE_TELLER_ENABLE = 'jaroslawzielinski_diagnostics/settings/store_teller_enable';

    public const PATH_SETTINGS_STORE_TELLER_POPUPMINWIDTH = 'jaroslawzielinski_diagnostics/settings/popup_min_width';

    public const PATH_SETTINGS_STORE_TELLER_BUTTONCOLOR = 'jaroslawzielinski_diagnostics/settings/button_color';

    public const PATH_SETTINGS_STORE_TELLER_BUTTONBACKGROUND = 'jaroslawzielinski_diagnostics/settings/button_background';

    public const PATH_SETTINGS_STORE_TELLER_POPUPCOLOR = 'jaroslawzielinski_diagnostics/settings/popup_color';

    public const PATH_SETTINGS_STORE_TELLER_POPUPBACKGROUND = 'jaroslawzielinski_diagnostics/settings/popup_background';

    public const PATH_PROGRESSBAR_FORMAT = 'jaroslawzielinski_diagnostics/progress_bar/format';

    //TODO: add Your constants here...
    public const PATH_SSLCERTIFICATES_CHECK_STAGING_DOMAINS =
        'jaroslawzielinski_diagnostics/ssl_certificates_check/staging_domains';

    public const PATH_SSLCERTIFICATES_CHECK_PROD_DOMAINS =
        'jaroslawzielinski_diagnostics/ssl_certificates_check/prod_domains';

    public const PATH_SSLCERTIFICATES_CHECK_WWW_DOMAINS =
        'jaroslawzielinski_diagnostics/ssl_certificates_check/www_domains';

    public const PATH_SSLCERTIFICATES_DAYS_TRIGGER = 'jaroslawzielinski_diagnostics/ssl_certificates_check/days_trigger';

    public const PATH_STORE_LOCALE = 'general/locale/code';

    /**
     * @var JsonSerializer
     */
    protected $jsonSerializer;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     */
    public function __construct(
        JsonSerializer $jsonSerializer,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->scopeConfig = $scopeConfig;
    }

    public function isStoreTellerEnable(): bool
    {
        return $this->scopeConfig
            ->isSetFlag(self::PATH_SETTINGS_STORE_TELLER_ENABLE, ScopeInterface::SCOPE_STORE);
    }

    public function getStoreTellerPopupMinWidth(): ?string
    {
        $popupMinWidth = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_STORE_TELLER_POPUPMINWIDTH, ScopeInterface::SCOPE_STORE);
        return empty($popupMinWidth) ? null : (string)$popupMinWidth;
    }

    public function getStoreTellerButtonColor(): ?string
    {
        $buttonColor = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_STORE_TELLER_BUTTONCOLOR, ScopeInterface::SCOPE_STORE);
        return empty($buttonColor) ? null : (string)$buttonColor;
    }

    public function getStoreTellerButtonBackground(): ?string
    {
        $buttonBackground = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_STORE_TELLER_BUTTONBACKGROUND, ScopeInterface::SCOPE_STORE);
        return empty($buttonBackground) ? null : (string)$buttonBackground;
    }

    public function getStoreTellerPopupColor(): ?string
    {
        $popupColor = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_STORE_TELLER_POPUPCOLOR, ScopeInterface::SCOPE_STORE);
        return empty($popupColor) ? null : (string)$popupColor;
    }

    public function getStoreTellerPopupBackground(): ?string
    {
        $popupBackground = $this->scopeConfig
            ->getValue(self::PATH_SETTINGS_STORE_TELLER_POPUPBACKGROUND, ScopeInterface::SCOPE_STORE);
        return empty($popupBackground) ? null : (string)$popupBackground;
    }

    public function getProgressBarFormat(): ?string
    {
        $progressBarFormat = $this->scopeConfig
            ->getValue(self::PATH_PROGRESSBAR_FORMAT);
        return empty($progressBarFormat) ? null : (string)$progressBarFormat;
    }

    public function getStagingDomains(): ?array
    {
        $serializedStagingDomains = Data::getSerializedArrayOrEmpty(
            $this->scopeConfig->getValue(self::PATH_SSLCERTIFICATES_CHECK_STAGING_DOMAINS)
        );
        return Data::prefixStringArray($this->jsonSerializer->unserialize($serializedStagingDomains), 'staging.');
    }

    public function getWwwDomains(): ?array
    {
        $serializedWwwDomains = Data::getSerializedArrayOrEmpty(
            $this->scopeConfig->getValue(self::PATH_SSLCERTIFICATES_CHECK_WWW_DOMAINS)
        );
        return Data::prefixStringArray($this->jsonSerializer->unserialize($serializedWwwDomains));
    }

    public function getDomains(): ?array
    {
        $domains = $this->getStagingDomains();
        if (!empty($this->getWwwDomains())) {
            $domains = array_merge($domains, $this->getWwwDomains());
        }
        return $domains;
    }

    public function getDaysTrigger(): int
    {
        return (int)(
            $this->scopeConfig->getValue(self::PATH_SSLCERTIFICATES_DAYS_TRIGGER, ScopeInterface::SCOPE_STORE) ?? '0'
        );
    }

    public function getStoreLocale(): ?string
    {
        $storeLocale = $this->scopeConfig->getValue(self::PATH_STORE_LOCALE, ScopeInterface::SCOPE_STORE);
        return empty($storeLocale) ? null : (string)$storeLocale;
    }
}
