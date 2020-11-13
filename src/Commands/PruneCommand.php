<?php

namespace Itop\Restic\Commands;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Itop\Restic\Restic\ResticFactory;

class PruneCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'restic:prune {repo} {--v1} {--v2}';

    /** @var string */
    protected $description = 'Run the prune.';

    public function handle()
    {
        consoleOutput()->comment('prune...');

        $restic = ResticFactory::buildPruneCommand(
            config('restic'), $this->argument('repo')
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