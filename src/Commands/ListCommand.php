<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ListCommand extends BaseCommand
{
    /** @var string */
    protected $signature = '
        restic:snapshots 
        {repo : Repository name specified in config}
        {--path= : filter the listing by directory path}
        {--host= : filter the listing by host}
        {--group-by= : Furthermore you can group the output by the same filters (host, paths, tags)}
        {--read-data= : By default, the check command does not verify that the actual pack files on disk in the repository are unmodified, because doing so requires reading a copy of every pack file in the repository. To tell restic to also verify the integrity of the pack files in the repository, use the --read-data flag}
        {--read-data-subset= : Use --read-data-subset=n/t to check only a subset of the repository pack files at a time. The parameter takes two values, n and t. When the check command runs, all pack files in the repository are logically divided in t (roughly equal) groups, and only files that belong to group number n are checked. For example, the following commands check all repository pack files over 5 separate invocations}
        {--v= : verbose level}
    ';

    /** @var string */
    protected $description = 'list all the snapshots stored in the repository';

    public function handle()
    {
        consoleOutput()
            ->comment("listing {$this->argument('repo')} snapshots");

        $restic = ResticFactory::buildListCommand(config('restic'), $this);

        try {

            $restic->run();
            
            consoleOutput()->info($restic->getProcessOutput());
  
        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}