<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class Domains extends AbstractFieldArray
{
    public const NAME = 'domain';

    /**
     * @inheritDoc
     */
    protected function _prepareToRender()
    {
        $this->addColumn(self::NAME, [
            'label' => __('Domain'),
            'class' => 'required-entry'
        ]);
        $this->_addAfter = true;
        $this->_addButtonLabel = __('Add domain');
    }
}
