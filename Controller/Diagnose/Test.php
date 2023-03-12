<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Controller\Diagnose;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
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
     * {@inheritDoc}
     * @throws \Exception
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('JaroslawZielinski Diagnose Test'));
        try {
            //TODO: diagnostic tests here
            $result = [];
            $this->eventManager->dispatch('diagnostics_controller_test', [
                'request' => $this->request,
                'result' => &$result
            ]);
            $this->logger->info('myTests controller test', $result);
        } catch (LocalizedException|\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }

        return $resultPage;
    }
}
