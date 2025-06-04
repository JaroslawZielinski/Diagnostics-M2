<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Console\ProductsSearch;

use JaroslawZielinski\Diagnostics\Model\Test\Test;
use Magento\Framework\Exception\LocalizedException;
use JaroslawZielinski\Diagnostics\Model\Config;
use JaroslawZielinski\Diagnostics\Model\Product\Find as FindProduct;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Process extends Test
{
    public const WEBSITE_ID = 'website';

    public const STORE_ID = 'store';

    public const ASSOC_COUNT = 'assoc_count';

    /**
     * @var FindProduct
     */
    private $findProduct;

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
        FindProduct $findProduct,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->findProduct = $findProduct;
        $this->config = $config;
        parent::__construct($logger);
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function execute(array $input, OutputInterface $output): array
    {
        $websiteId = $input[self::WEBSITE_ID];
        if (empty($websiteId)) {
            throw new LocalizedException(__('Write website ID first.'));
        }
        $storeId = $input[self::STORE_ID];
        if (empty($storeId)) {
            throw new LocalizedException(__('Write store ID first.'));
        }
        $assocCount = $input[self::ASSOC_COUNT];
        if (empty($assocCount)) {
            throw new LocalizedException(__('Write associated products count firs.'));
        }
        $this->output = $output;
        try {
            $found = $this->findProduct->execute(
                (int)$websiteId,
                (int)$storeId,
                (int)$assocCount,
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
            __('OK. In Custom process found \'%1\' of products', $found)
        ];
    }

    /**
     * @inheritDoc
     */
    public function getArgumentsDefinition(): array
    {
        return [
            [
                'name' => self::WEBSITE_ID,
                'mode' => InputArgument::OPTIONAL,
                'description' => (string)__('Website ID'),
                'default' => ''
            ],
            [
                'name' => self::STORE_ID,
                'mode' => InputArgument::OPTIONAL,
                'description' => (string)__('Store ID'),
                'default' => ''
            ],
            [
                'name' => self::ASSOC_COUNT,
                'mode' => InputArgument::OPTIONAL,
                'description' => (string)__('Associated Count'),
                'default' => 2
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'jaroslawzielinski:associated-products:find';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Find Associated Products By Count (default=2) Process';
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
