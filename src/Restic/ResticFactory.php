<?php

namespace Itop\Restic\Restic;

use Illuminate\Support\Collection;

class ResticFactory
{
    public static function buildInitCommand(array $config, string $repository): Restic
    {
        $r = $config['repositories'][$repository];

        return  (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->addCommand('init')
                ->setEnvironment($r['env']);
    }

    public static function buildBackupCommand(array $config, string $repository): Restic
    {
        $r = $config['repositories'][$repository];

        return  (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->shouldBackup($r['backup_paths'])
                ->excludeFilesFrom($r['exclude_files'])
                ->setEnvironment($r['env']);
    }

    public static function buildPruneCommand(array $config, string $repository): Restic
    {
        $r = $config['repositories'][$repository];

        return  (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->addCommand('prune')
                ->setEnvironment($r['env']);
    }

    public static function buildListCommand(array $config, string $repository): Restic
    {
        $r = $config['repositories'][$repository];

        return  (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->addCommand('snapshots')
                ->setEnvironment($r['env']);
    }

    public static function buildRestoreCommand(
        array $config, 
        string $repository,
        string $snapshot,
        string $destination
    ): Restic {

        $r = $config['repositories'][$repository];

        return (new Restic())
                ->addCommand($config['restic_binary_path'])
                ->addCommand('restore')
                ->addCommand($snapshot)
                ->addCommand('--target')
                ->addCommand($destination)
                ->setEnvironment($r['env']);
    }
}
