<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class KeyCommand extends BaseCommand
{
    /** @var string */
    protected $signature = '
        restic:key 
        {repo : Repository name specified in config}
        {action : You can use the list, add, remove, and passwd (changes a password) sub-commands to manage the keys}
        {--v= : verbose level}
    ';

    /** @var string */
    protected $description = 'The key command allows you to set multiple access keys or passwords per repository.';

    public function handle()
    {
        consoleOutput()
            ->comment("{$this->argument('repo')} key {$this->argument('action')} ");

        $restic = ResticFactory::buildKeyCommand(config('restic'), $this);

        try {

            $restic->run();
            
            consoleOutput()->info($restic->getProcessOutput());
  
        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}