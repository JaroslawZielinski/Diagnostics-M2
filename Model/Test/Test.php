<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Test;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;

class Test
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * TODO: a method for Console call
     */
    public function execute(array $input): array
    {
        $testName = 'test';
        $testValue = $input[$testName] ?? null;
        $this->logger->info('Test Input', [$testName => $testValue]);
        return [
            __('Done. [%1: %2]', $testName, $testValue)
        ];
    }

    /**
     */
    public function getArgumentsDefinition(): array
    {
        return [
            [
                'name' => 'test',
                'mode' => InputArgument::REQUIRED,
                'description' => __('Test argument'),
                'default' => null
            ]
        ];
    }
}
