<?php

namespace Fooino\Lodestar\Console;

use Fooino\Lodestar\Actions\SyncStandardsAction;
use Fooino\Lodestar\Enums\Tool;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use InvalidArgumentException;
use Throwable;

#[AsCommand(name: 'sync', description: 'Generate AGENTS.md and optional tool adapters for the current package.')]
class SyncStandardsCommand extends Command
{
    /**
     * Configure the sync command
     */
    protected function configure(): void
    {
        $this->addOption(
            name: 'tool',
            shortcut: null,
            mode: InputOption::VALUE_OPTIONAL,
            description: 'Tool adapter to generate. Defaults to all.',
            default: Tool::All->value
        );
    }

    /**
     * Execute the sync command
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            $this->sync(input: $input, output: $output);

            return Command::SUCCESS;

            //
        } catch (Throwable $exception) {

            $output->writeln(messages: '<error>' . $exception->getMessage() . '</error>');

            return Command::FAILURE;
        }
    }

    /**
     * Run the sync action and report the updated files
     */
    private function sync(InputInterface $input, OutputInterface $output): void
    {
        $updated = (new SyncStandardsAction())->run(tool: $this->tool(input: $input));

        foreach ($updated as $file) {

            $output->writeln(messages: '<info>' . $file . ' updated</info>');
        }
    }

    /**
     * Resolve the tool option to an enum case
     */
    private function tool(InputInterface $input): Tool
    {
        $value = $input->getOption(name: 'tool');

        $tool = Tool::tryFrom(value: (string) $value);

        if ($tool === null) {

            throw new InvalidArgumentException(message: 'Unknown tool: ' . $value);
        }

        return $tool;
    }
}
