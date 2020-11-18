<?php

namespace Itop\Restic\Commands;

use Itop\Restic\Restic\ResticFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ForgetCommand extends BaseCommand
{
    /** @var string */
    protected $signature = '
        restic:forget 
        {repo : Repository name specified in config}
        {snapshot? : snapshot to remove} 
        {--keep-last= : never delete the n last (most recent) snapshots} 
        {--keep-hourly= : for the last n hours in which a snapshot was made, keep only the last snapshot for each hour.} 
        {--keep-daily= : for the last n days which have one or more snapshots, only keep the last one for that day.} 
        {--keep-weekly= : for the last n weeks which have one or more snapshots, only keep the last one for that week.} 
        {--keep-monthly= : for the last n months which have one or more snapshots, only keep the last one for that month.} 
        {--keep-yearly= : for the last n years which have one or more snapshots, only keep the last one for that year.} 
        {--keep-tag= : keep all snapshots which have all tags specified by this option (can be specified multiple times).}
        {--tag=*}
        {--keep-within= : keep all snapshots which have been made within the duration of the latest snapshot. duration needs to be a number of years, months, days, and hours, e.g. 2y5m7d3h will keep all snapshots made in the two years, five months, seven days, and three hours before the latest snapshot.} 
        {--group-by=} 
        {--prune}
        {--dry-run : instructs restic to not remove anything but print which snapshots would be removed.}
        {--v= : verbose level}
    ';

    /** @var string */
    protected $description = 'All backup space is finite, so restic allows removing old snapshots. This can be done either manually (by specifying a snapshot ID to remove) or by using a policy that describes which snapshots to forget. For all remove operations, two commands need to be called in sequence: forget to remove a snapshot and prune to actually remove the data that was referenced by the snapshot from the repository. This can be automated with the --prune option of the forget command, which runs prune automatically if snapshots have been removed.';

    public function handle()
    {
        consoleOutput()
            ->comment("removing snapshots from {$this->argument('repo')} repository");

        $restic = ResticFactory::buildForgetCommand(config('restic'), $this);

        try {

            $restic->run();
            
            consoleOutput()->info($restic->getProcessOutput());
  
        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}