<?php

namespace Itop\Restic;

use Illuminate\Support\ServiceProvider;
use Itop\Restic\Commands\InitCommand;
use Itop\Restic\Commands\BackupCommand;
use Itop\Restic\Commands\PruneCommand;
use Itop\Restic\Commands\ListCommand;
use Itop\Restic\Commands\RestoreCommand;
use Itop\Restic\Commands\ForgetCommand;
use Itop\Restic\Commands\CheckCommand;
use Itop\Restic\Helpers\ConsoleOutput;

class BackupServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/restic.php' => config_path('restic.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/restic.php', 'restic');

        $this->app->bind('command.restic:init', InitCommand::class);
        $this->app->bind('command.restic:backup', BackupCommand::class);
        $this->app->bind('command.restic:prune', PruneCommand::class);
        $this->app->bind('command.restic:snapshots', ListCommand::class);
        $this->app->bind('command.restic:restore', RestoreCommand::class);
        $this->app->bind('command.restic:forget', ForgetCommand::class);
        $this->app->bind('command.restic:check', CheckCommand::class);

        $this->commands([
            'command.restic:init',
            'command.restic:backup',
            'command.restic:prune',
            'command.restic:snapshots',
            'command.restic:restore',
            'command.restic:forget',
            'command.restic:check'
        ]);

        $this->app->singleton(ConsoleOutput::class);
    }
}
