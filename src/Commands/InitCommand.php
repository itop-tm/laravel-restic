<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class InitCommand extends BaseCommand
{
    /** @var string */
    protected $signature = '
        restic:init 
        {repo : Repository name specified in config}
        {--v= : verbose level}
    ';

    /** @var string */
    protected $description = 'Preparing a new repository';

    public function handle()
    {
        consoleOutput()
            ->comment("Preparing a {$this->argument('repo')} repository ...");

        $restic = ResticFactory::buildInitCommand(config('restic'), $this);

        try {

            $restic->run();
            
            consoleOutput()->info($restic->getProcessOutput());

        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}