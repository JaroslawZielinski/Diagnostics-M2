<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Block\Adminhtml;

use JaroslawZielinski\Diagnostics\Model\Config;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class Test extends Template
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @inheritDoc
     */
    public function __construct(
        Config $config,
        Context $context,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        $this->config = $config;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }
}
