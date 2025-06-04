<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Console\Command;

use JaroslawZielinski\Diagnostics\Console\Command\Test;
use JaroslawZielinski\Diagnostics\Model\Console\CustomerWithoutPassword\Create as TestModel;
use Psr\Log\LoggerInterface;

class CreateCustomerWithoutPasswordCommand extends Test
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
