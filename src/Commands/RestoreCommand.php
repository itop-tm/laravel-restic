<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RestoreCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'restic:restore {repo} {snapshot} {destination} {--v1} {--v2}';

    /** @var string */
    protected $description = 'restoring from backup';

    public function handle()
    {
        consoleOutput()
            ->comment(
                "restoring Snapshot {$this->argument('snapshot')} to {$this->argument('destination')}"
            );

        $restic = ResticFactory::buildRestoreCommand(
                        config('restic'), 
                        $this->argument('repo'),
                        $this->argument('snapshot'),
                        $this->argument('destination')
                    );

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