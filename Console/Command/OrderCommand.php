<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Console\Command;

use JaroslawZielinski\Diagnostics\Model\Console\OrderProcessing as TestModel;
use Psr\Log\LoggerInterface;

class OrderCommand extends Test
{
    /**
     * @inheritDoc
     */
    public function __construct(
        LoggerInterface $logger,
        TestModel $testModel
    ) {
        parent::__construct($logger, $testModel);
    }
}
