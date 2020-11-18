<?php

namespace Itop\Restic\Commands;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Itop\Restic\Restic\ResticFactory;

class PruneCommand extends BaseCommand
{
    /** @var string */
    protected $signature = '
        restic:prune
        {repo : Repository name specified in config}
        {--max-unused= : limit allow unused data up to the specified limit within the repository. This allows restic to keep partly used files instead of repacking them.}
        {--max-repack-size= : if set limits the total size of files to repack. As prune first stores all repacked files and deletes the obsolete files at the end, this option might be handy if you expect many files to be repacked and fear to run low on storage.}
        {--repack-cacheable-only= : if set to true only files which contain metadata and would be stored in the cache are repacked. Other pack files are not repacked if this option is set. This allows a very fast repacking using only cached data. It can, however, imply that the unused data in your repository exceeds the value given by --max-unused. The default value is false.}
        {--v= : verbose level}
    ';

    /** @var string */
    protected $description = 'Run the prune.';

    public function handle()
    {
        consoleOutput()
            ->comment("prune... {$this->argument('repo')}");

        $restic = ResticFactory::buildPruneCommand(config('restic'), $this);

        try {

            $restic->run();
            
            consoleOutput()->info($restic->getProcessOutput());

        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}