<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Console\Command;

use JaroslawZielinski\Diagnostics\Console\Command\Test;
use JaroslawZielinski\Diagnostics\Model\Console\ProductsSearch\Process as TestModel;
use Psr\Log\LoggerInterface;

class ProductSearchProcessCommand extends Test
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
