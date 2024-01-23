<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Console;

use JaroslawZielinski\Diagnostics\Model\Config;
use JaroslawZielinski\Diagnostics\Model\Test\Test;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use JaroslawZielinski\Diagnostics\Model\Console\OrderProcessing\AverageTime as AverageOrderProcessingTime;

class OrderProcessing extends Test
{
    /**
     * @var AverageOrderProcessingTime
     */
    private $averageOrderProcessingTime;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ProgressBar
     */
    private $progressBar;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @inheritDoc
     */
    public function __construct(
        AverageOrderProcessingTime $averageOrderProcessingTime,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->averageOrderProcessingTime = $averageOrderProcessingTime;
        $this->config = $config;
        parent::__construct($logger);
    }

    /**
     * @inheritDoc
     */
    public function execute(array $input, OutputInterface $output): array
    {
        $this->output = $output;
        try {
            $averageProcessingTime = $this->averageOrderProcessingTime->execute(
                [$this, 'start'],
                [$this, 'iteration'],
                [$this, 'end']
            );
        } catch (\Exception|LocalizedException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [
                __('Something went wrong [message: %1]', $e->getMessage())
            ];
        }
        return [
            __('OK. Average Order Processing Time is \'%1\' days.', $averageProcessingTime)
        ];
    }

    /**
     * @inheritDoc
     */
    public function getArgumentsDefinition(): array
    {
        return [
        ];
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'jaroslawzielinski:average-order-processing:time';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Analyze the time between the order creation date and the order completion date. This is important to ' .
            'identify any delays in the delivery or payment process.';
    }

    public function start(int $max): void
    {
        $consoleOutput = new ConsoleOutput();
        $this->progressBar = new ProgressBar($consoleOutput, $max);
        $format = $this->config->getProgressBarFormat();
        $this->progressBar->setFormat($format);
        // Start the progress bar
        $this->progressBar->start();
    }

    public function iteration(): void
    {
        $this->progressBar->advance();
    }

    public function end(): void
    {
        $this->progressBar->finish();
        $this->output->writeln('');
    }
}
