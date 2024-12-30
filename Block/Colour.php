<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Colour extends Field
{
    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $html = $element->getElementHtml();
        $value = $element->getData('value');
        $html .= '<script type="text/javascript">
            require(["jquery", "jquery/colorpicker/js/colorpicker"], function ($) {
                $(document).ready(function () {
                    var thisElement = $("#' . $element->getHtmlId() . '");
                    thisElement.css("backgroundColor", "'. $value .'");
                    thisElement.ColorPicker({
                        color: "'. $value .'",
                        onChange: function (hsb, hex, rgb) {
                            thisElement.css("backgroundColor", "#" + hex).val("#" + hex);
                        }
                    });
                });
            });
            </script>';
        return $html;
    }
}
