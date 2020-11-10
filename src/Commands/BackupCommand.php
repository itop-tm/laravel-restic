<?php

namespace Itop\Restic\Commands;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupCommand extends BaseCommand
{
    /** @var string */
    protected $signature = 'restic:backup';

    /** @var string */
    protected $description = 'Run the backup.';

    public function handle()
    {
        consoleOutput()->comment('Starting backup...');

        $process = new Process([
            $this->restic,
            'backup',
            $this->backupPaths,
            "--exclude={$this->exclude}"
        ]);

        $process->setTimeout(3600);
        
        try {

            $process->mustRun(null, $this->env);
            
            consoleOutput()->info( $process->getOutput() );
  
        } catch (ProcessFailedException $th) {
            consoleOutput()->error($th->getMessage());
        }
    }
}