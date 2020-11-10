<?php

namespace Itop\Restic\Commands;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PruneCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'restic:prune';

    /** @var string */
    protected $description = 'Run the prune.';

    public function handle()
    {
        consoleOutput()->comment('prune...');

        $process = new Process(['restic', 'prune']);

        $process->setTimeout(3600);
        
        try {

            $process->mustRun(null, $this->env);
            
            consoleOutput()->info($process->getOutput());

        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}