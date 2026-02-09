<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Block\Adminhtml;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * @see https://jigarkarangiya.com/draggable-dynamic-rows-in-magento2-system-configuration/
 */
abstract class AbstractDraggableFieldArray extends AbstractFieldArray
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();

        $this->setTemplate('JaroslawZielinski_Diagnostics::system/config/form/field/draggable.phtml');
    }

    /**
     */
    abstract protected function getRowsHtmlId(): string;

    /**
     * @inheritDoc
     */
    protected function _toHtml(): string
    {
        $rowHtmlId = str_replace('row_', '', $this->getRowsHtmlId());
        $html = parent::_toHtml();
        $styles = <<<style
<style>
    #{$rowHtmlId} tbody tr td:first-child { min-width: 293px; }
</style>
style;
        return $html . $styles;
    }

    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $html = parent::_getElementHtml($element);
        $rowsHtmlID = $this->getRowsHtmlId();
        $script = <<<EOT
            <script>
                document.addEventListener('DOMContentLoaded', function(event) {
                    require([
                        'jquery',
                        'Magento_Theme/js/sortable'
                    ], function ($) {
                        setTimeout(function () {
                            $('#{$rowsHtmlID}').sortable({
                                containment: 'parent',
                                items: 'tbody tr',
                                tolerance: 'pointer',
                            });
                        }, 1000);
                    });
                });
            </script>
EOT;
        $html .= $script;
        return $html;
    }
}
