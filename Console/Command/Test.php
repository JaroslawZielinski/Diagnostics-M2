<?php

declare(strict_types=1);

namespace JaroslawZielinski\Diagnostics\Console\Command;

use JaroslawZielinski\Diagnostics\Model\Test\Test as TestModel;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Test extends Command
{
    /**
     * @var array
     */
    private $messages;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var TestModel
     */
    private $testModel;

    /**
     * @inheirtDoc
     */
    public function __construct(
        LoggerInterface $logger,
        TestModel $testModel
    ) {
        $this->messages = [];
        $this->logger = $logger;
        $this->testModel = $testModel;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName($this->testModel->getName());
        $this->setDescription($this->testModel->getDescription());
        foreach ($this->testModel->getArgumentsDefinition() as $argument) {
            $this->addArgument(
                (string)$argument['name'],
                $argument['mode'],
                (string)$argument['description'],
                $argument['default']
            );
        }
    }

    private function displayMessages(OutputInterface $output): int
    {
        foreach ($this->messages as $message) {
            $output->writeln($message);
        }
        return 1;
    }

    private function addMessage(string $message, ...$args): void
    {
        $this->messages[] = sprintf($message, ...$args);
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $inputValues = [];
            foreach ($this->testModel->getArgumentsDefinition() as $arguments) {
                $name = (string)($arguments['name'] ?? null);
                if (empty($name)) {
                    continue;
                }
                $inputValues[$name] = $input->getArgument($name);
            }
            $messages = $this->testModel->execute($inputValues);
            foreach ($messages as $message) {
                $this->addMessage((string)$message);
            }
        } catch (\Exception $e) {
            $message = sprintf('<fg=red;options=bold>Something went wrong</>: <fg=white>\'%s\'</>: <fg=yellow>%s</>.', $e->getMessage(), $e->getTraceAsString());
            $this->logger->error($e->getMessage(), $e->getTrace());
            $output->writeln($message);
            return 1;
        }
        return $this->displayMessages($output);
    }
}
