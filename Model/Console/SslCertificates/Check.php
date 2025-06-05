<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Console\SslCertificates;

use JaroslawZielinski\Diagnostics\Model\Test\Test;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use JaroslawZielinski\Diagnostics\Helper\Data;
use JaroslawZielinski\Diagnostics\Model\Config;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\Filesystem\DirectoryList;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Module\FullModuleList;

class Check extends Test
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var FullModuleList
     */
    private $fullModuleList;

    /**
     * @var State
     */
    private $state;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var File
     */
    private $file;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $script;

    /**
     * @inheritDoc
     */
    public function __construct(
        FullModuleList $fullModuleList,
        State $state,
        Filesystem $filesystem,
        File $file,
        Config $config,
        string $script,
        LoggerInterface $logger
    ) {
        $this->fullModuleList = $fullModuleList;
        $this->state = $state;
        $this->filesystem = $filesystem;
        $this->file = $file;
        $this->config = $config;
        $this->script = $script;
        parent::__construct($logger);
    }

    public function commandLine(string $rootDir): array
    {
        $result = [];
        foreach ($this->config->getDomains() as $domain) {
            $rawOutput = [];
            $command = sprintf('cd %s && sh %s %s', $rootDir, $this->script, $domain);
            exec($command, $rawOutput);
            $output = reset($rawOutput);

            $result[$domain] = $output;
        }
        return $result;
    }

    /**
     * @throws \Exception
     */
    private function renderResult(array $result): array
    {
        $today = new \DateTime();
        $daysTrigger = $this->config->getDaysTrigger();
        $output = [
            __(
                'SSL Certificate Check Expiration Date [today: %1, days trigger: %2]: ',
                $today->format(self::DATE_FORMAT),
                $daysTrigger
            ),
            ''
        ];
        /**
         * @var string $domain
         * @var string $expire
         */
        foreach ($result as $domain => $expire) {
            $date = new \DateTime($expire);
            $isAlert = Data::dateDaysDiff($today, $date) <= $daysTrigger;
            $colorOption = $isAlert ? 'red;options=bold' : 'green';
            $textOption = $isAlert ? ' <------- x' : '✓';
            $output[] = __(
                '<fg=yellow>%1</>: <fg=%2>%3</>%4',
                $domain,
                $colorOption,
                $date->format(self::DATE_FORMAT),
                $textOption
            );
        }

        return $output;
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function execute(array $input, OutputInterface $output): array
    {
        try {
            $rootDir = $this->filesystem->getDirectoryRead(DirectoryList::ROOT)->getAbsolutePath();
            $rootDir .= 'vendor/jaroslawzielinski/diagnostics-m2/scripts';
            $result = $this->state->emulateAreaCode(Area::AREA_ADMINHTML, [$this, 'commandLine'], [$rootDir]);
            $this->logger->info('SSL Certificates Check', $result);
        } catch (\Exception|LocalizedException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return [
                __('Something went wrong [message: %1]', $e->getMessage())
            ];
        }
        return $this->renderResult($result);
    }

    /**
     * @inheritDoc
     */
    public function getArgumentsDefinition(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'jaroslawzielinski:ssl-certificates:check';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Diagnostics SSL Certificates Check';
    }
}
