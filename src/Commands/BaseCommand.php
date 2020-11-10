<?php

namespace Itop\Restic\Commands;

use Illuminate\Console\Command;
use Itop\Restic\Helpers\ConsoleOutput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    public function run(InputInterface $input, OutputInterface $output): int
    {
        app(ConsoleOutput::class)->setOutput($this);

        $this->setUpEnv();

        return parent::run($input, $output);
    }

    protected function setUpEnv()
    {
        $this->restic = config('restic.restic_binary_path');
        $this->backupPaths = stringify(config('restic.backup_paths'));
        $this->exclude = stringify(config('restic.exclude_files'));
        $this->env = [];

        foreach (config('restic.env') as $key => $v) {
            $this->env[strtoupper($key)] = is_array($v) ? stringify($v) : $v;
        }
    }
}
