<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'restic:backup {repo} {--v1} {--v2}';

    /** @var string */
    protected $description = 'Run the backup.';

    public function handle()
    {
        consoleOutput()
            ->comment("Starting backup {$this->argument('repo')}");

        $restic = ResticFactory::buildBackupCommand(config('restic'), $this->argument('repo'));

        if ($this->option('v1') == '1') {
            $restic->addCommand('--verbose');
        }

        if ($this->option('v2') == '2') {
            $restic->addCommand('--verbose=2');
        }

        try {

            $restic->run();
            
            consoleOutput()->info($restic->getProcessOutput());
  
        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}