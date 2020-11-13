<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class InitCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'restic:init {repo} {--v1} {--v2}';

    /** @var string */
    protected $description = 'Preparing a new repository';

    public function handle()
    {
        consoleOutput()
            ->comment("Preparing a {$this->argument('repo')} repository ...");

        $restic = ResticFactory::buildInitCommand(
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