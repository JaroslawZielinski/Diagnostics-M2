<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Test;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

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
    public function execute(array $input, OutputInterface $output): array
    {
        $testName = 'test';
        $testValue = $input[$testName] ?? null;
        $output->writeln(sprintf('Test Input \'%s\' = \'%s\'', $testName, $testValue));
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
                'description' => (string)__('Test argument'),
                'default' => null
            ]
        ];
    }

    /**
     */
    public function getName(): string
    {
        return 'jaroslawzielinski:diagnostics:test';
    }

    /**
     */
    public function getDescription(): string
    {
        return 'JaroslawZielinski Diagnostics test';
    }
}
