<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Plugin\Backend\Model\Menu;

use Magento\Backend\Model\Menu\Item;
use Magento\Framework\UrlInterface;

class ItemPlugin
{

    /**
     * @var UrlInterface
     */
    private $frontUrlModel;

    public function __construct(UrlInterface $frontUrlModel)
    {
        $this->frontUrlModel = $frontUrlModel;
    }

    public function afterGetUrl(Item $subject, $result)
    {
        $info = $subject->toArray();
        if ('JaroslawZielinski_Diagnostics::diagnoseFrontend' === $info['resource']) {
            return $this->frontUrlModel->getUrl($info['action']);
        }
        return $result;
    }
}
