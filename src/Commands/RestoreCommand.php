<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RestoreCommand extends BaseCommand
{
    /** @var string */
    protected $signature = '
        restic:restore 
        {repo : Repository name specified in config}
        {snapshot : snapshot to restore} 
        {--target= : destination}
        {--host= : --host and --path filters to choose the last backup for a specific host, path or both}
        {--path=}
        {--exclude=* : Use --exclude and --include to restrict the restore to a subset of files in the snapshot. For example, to restore a single file}
        {--include=*} 
        {--v= : verbose level}
    ';

    /** @var string */
    protected $description = 'restoring from backup';

    public function handle()
    {
        consoleOutput()
            ->comment(
                "restoring Snapshot {$this->argument('snapshot')} to {$this->option('target')}"
            );

        $restic = ResticFactory::buildRestoreCommand(config('restic'), $this);

        try {

            $restic->run();
            
            consoleOutput()->info($restic->getProcessOutput());
  
        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}