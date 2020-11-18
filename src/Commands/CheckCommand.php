<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CheckCommand extends BaseCommand
{
    /** @var string */
    protected $signature = '
        restic:check 
        {repo : Repository name specified in config}
        {--v= : verbose level}
    ';

    /** @var string */
    protected $description = 'Verify that all data is properly stored in the repository';

    public function handle()
    {
        consoleOutput()->comment("checking {$this->argument('repo')}");

        $restic = ResticFactory::buildCheckCommand(config('restic'), $this);

        try {

            $restic->run();
            
            consoleOutput()->info($restic->getProcessOutput());
  
        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}