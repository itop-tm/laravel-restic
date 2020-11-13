<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ListCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'restic:snapshots {repo} {--v1} {--v2}';

    /** @var string */
    protected $description = 'listing snapshots';

    public function handle()
    {
        consoleOutput()->comment('listing snapshots');

        $restic = ResticFactory::buildListCommand(config('restic'), $this->argument('repo'));

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