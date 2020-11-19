<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupCommand extends BaseCommand
{
    /** @var string */
    protected $signature = '
        restic:backup 
        {repo : Repository name specified in config}
        {--exclude=* : Specified one or more times to exclude one or more items}
        {--iexclude=* : Same as --exclude but ignores the case of paths}
        {--exclude-caches= : Specified once to exclude folders containing a special file}
        {--exclude-file= : Specified one or more times to exclude items listed in a given file}
        {--iexclude-file= : --iexclude-file Same as exclude-file but ignores cases like in --iexclude}
        {--exclude-if-present=* : foo Specified one or more times to exclude a folder’s content if it contains a file called foo (optionally having a given header, no wildcards for the file name supported)}
        {--exclude-larger-than= : Specified once to excludes files larger than the given size}
        {--one-file-system= : By specifying the option --one-file-system you can instruct restic to only backup files from the file systems the initially specified files or directories reside on. In other words, it will prevent restic from crossing filesystem boundaries when performing a backup.}
        {--files-from : By using the --files-from option you can read the files you want to back up from one or more files. This is especially useful if a lot of files have to be backed up that are not in the same folder or are maybe pre-filtered by other software.}
        {--ignore-inode : In filesystems that do not support inode consistency, like FUSE-based ones and pCloud, it is possible to ignore inode on changed files comparison by passing --ignore-inode to backup command.}
        {--with-atime : By default, restic does not save the access time (atime) for any files or other items, since it is not possible to reliably disable updating the access time by restic itself. This means that for each new backup a lot of metadata is written, and the next backup needs to write new metadata again. If you really want to save the access time for files and directories, you can pass the --with-atime option to the backup command.}
        {--v= : verbose level}
    ';

    /** @var string */
    protected $description = 'Run the backup.';

    public function handle()
    {
        consoleOutput()
            ->comment("Starting backup {$this->argument('repo')}");
        
        $restic = ResticFactory::buildBackupCommand(config('restic'), $this);

        try {

            $restic->run();
            
            consoleOutput()
                ->info($restic->getProcessOutput());
  
        } catch (ProcessFailedException $th) {
            consoleOutput()
                ->error($th->getMessage());
        }
    }
}