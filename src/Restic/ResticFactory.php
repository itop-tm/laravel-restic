<?php

namespace Itop\Restic\Restic;

use Illuminate\Support\Collection;

class ResticFactory
{
    public static function buildInitCommand(array $config, $c): Restic
    {
        $r = $config['repositories'][$c->argument('repo')];

        return (new Restic())
                    ->addCommand($config['restic_binary_path'])
                    ->addCommand('init')
                    ->setEnvironment($r['env'])
                    ->setVerbose($c->option('v'));
    }

    public static function buildBackupCommand(array $config, $c): Restic
    {
        $r = $config['repositories'][$c->argument('repo')];

        $restic = (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->shouldBackup($r['backup_paths'])
                ->setEnvironment($r['env']);

        if (!$c->option('exclude') && isset($r['exclude_files'][0])) {
            $restic->excludeFilesFrom($r['exclude_files']);
        }

        foreach($c->options() as $name => $value) {
            $restic->autoAddOptions($name, $value);
        }

        $restic->setVerbose($c->option('v'));
        
        return $restic;
    }

    public static function buildPruneCommand(array $config, $c): Restic
    {
        $r = $config['repositories'][$c->argument('repo')];

        $restic = (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->addCommand('prune')
                ->setEnvironment($r['env'])
                ->setVerbose($c->option('v'));

        foreach($c->options() as $name => $value) {
            $restic->autoAddOptions($name, $value);
        }
        
        return $restic;
    }

    public static function buildListCommand(array $config, $c): Restic
    {
        $r = $config['repositories'][$c->argument('repo')];

        return  (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->addCommand('snapshots')
                ->setEnvironment($r['env'])
                ->setVerbose($c->option('v'));
    }

    public static function buildRestoreCommand(array $config, $c): Restic {

        $r = $config['repositories'][$c->argument('repo')];

        return (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->addCommand('restore')
                ->addCommand($c->argument('snapshot'))
                ->addCommand('--target')
                ->addCommand($c->option('target'))
                ->setEnvironment($r['env'])
                ->setVerbose($c->option('v'));
    }

    public static function buildCheckCommand(array $config, $c): Restic
    {
        $r = $config['repositories'][$c->argument('repo')];

        return (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->addCommand('check')
                ->setEnvironment($r['env'])
                ->setVerbose($c->option('v'));
    }

    public static function buildForgetCommand(array $config, $c): Restic
    {
        $r = $config['repositories'][$c->argument('repo')];

        $restic = (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->addCommand('forget')
                ->setEnvironment($r['env']);

        if ($c->argument('snapshot')) {
            $restic->addCommand($c->argument('snapshot'));
        }

        foreach($c->options() as $name => $value) {
            $restic->autoAddOptions($name, $value);
        }
        
        $restic->setVerbose($c->option('v'));
        
        return $restic;
    }
}
