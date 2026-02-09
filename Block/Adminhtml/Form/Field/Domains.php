<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use JaroslawZielinski\Diagnostics\Block\Adminhtml\AbstractDraggableFieldArray;

class Domains extends AbstractDraggableFieldArray
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

    /**
     * @inheritDoc
     */
    protected function getRowsHtmlId(): string {
        return 'row_jaroslawzielinski_diagnostics_ssl_certificates_check_staging_domains';
    }
}
