<?php

namespace Itop\Restic;

use Illuminate\Support\ServiceProvider;
use Itop\Restic\Commands\InitCommand;
use Itop\Restic\Commands\BackupCommand;
use Itop\Restic\Commands\PruneCommand;
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

        $this->commands([
            'command.restic:init',
            'command.restic:backup',
            'command.restic:prune',
        ]);

        $this->app->singleton(ConsoleOutput::class);
    }
}
