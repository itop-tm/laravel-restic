<?php

namespace Itop\Restic\Commands;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
// use Itop\Restic\Exceptions\InvalidCommand;

class InitCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'restic:init';

    /** @var string */
    protected $description = 'Preparing a new repository';

    public function handle()
    {
        consoleOutput()->comment('Preparing a new repository ...');

        $process = new Process(['restic', 'init']);
        $process->setTimeout(3600);
        
        try {

            $process->mustRun(null, $this->env);
            
            consoleOutput()->info($process->getOutput());

     
        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}