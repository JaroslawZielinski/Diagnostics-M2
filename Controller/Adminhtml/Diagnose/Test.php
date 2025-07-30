<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Controller\Adminhtml\Diagnose;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\Page;
use Psr\Log\LoggerInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;

class Test extends Action
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EventManager
     */
    private $eventManager;
    /**
     * @var Http
     */
    private $request;

    /**
     * @inheritDoc
     */
    public function __construct(
        LoggerInterface $logger,
        EventManager $eventManager,
        Http $request,
        Context $context
    ) {
        $this->logger = $logger;
        $this->eventManager = $eventManager;
        $this->request = $request;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $title = __('JaroslawZielinski Diagnose Test');
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('JaroslawZielinski_Diagnostics::menu');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $resultPage->addBreadcrumb(__('Diagnostics'), $title);
        try {
            //TODO:
            $result = [];
            $this->eventManager->dispatch('adminhtml_diagnostics_controller_test', [
                'request' => $this->request,
                'result' => &$result
            ]);
            $this->logger->info('myTests adminhtml controller test', $result);
        } catch (LocalizedException|\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
        return $resultPage;
    }

    /**
     * @inheritDoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('JaroslawZielinski::diagnose');
    }
}
