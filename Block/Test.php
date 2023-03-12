<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Block;

use JaroslawZielinski\Diagnostics\Model\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

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
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }
}
