<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Model\Console;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

class Output extends \Symfony\Component\Console\Output\Output
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @inheritDoc
     */
    public function __construct(
        LoggerInterface $logger,
        ?int $verbosity = self::VERBOSITY_NORMAL,
        bool $decorated = false,
        OutputFormatterInterface $formatter = null
    ) {
        $this->logger = $logger;
        parent::__construct($verbosity, $decorated, $formatter);
    }

    /**
     * @inheritDoc
     */
    protected function doWrite(string $message, bool $newline)
    {
        $this->logger->info($message);
    }
}
