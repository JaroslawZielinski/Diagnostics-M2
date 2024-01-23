<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Format implements ArrayInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'normal', 'label' => __('normal')],
            ['value' => 'normal_nomax', 'label' => __('normal_nomax')],
            ['value' => 'verbose', 'label' => __('verbose')],
            ['value' => 'verbose_nomax', 'label' => __('verbose_nomax')],
            ['value' => 'very_verbose', 'label' => __('very_verbose')],
            ['value' => 'very_verbose_nomax', 'label' => __('very_verbose_nomax')],
            ['value' => 'debug', 'label' => __('debug')],
            ['value' => 'debug_nomax', 'label' => __('debug_nomax')]
        ];
    }
}
