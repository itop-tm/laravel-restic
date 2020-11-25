# Schedule your backup using laravel and restic

This package provides a class to help scheduling your backup using [restic](https://github.com/restic/restic)

# Requirements

### Go installation
Before using this package you have to install go and restic

[Go](https://golang.org) is an open source programming language that makes it easy to build simple, reliable, and efficient software.

If you have a previous version of Go installed, be sure to remove it before installing another.

Download the archive and extract it into /usr/local, creating a Go tree in /usr/local/go.
For example, run the following as root or through sudo:

```bash
tar -C /usr/local -xzf go1.15.5.linux-amd64.tar.gz
```

Add /usr/local/go/bin to the PATH environment variable.
You can do this by adding the following line to your $HOME/.profile or /etc/profile (for a system-wide installation):

```bash
export PATH=$PATH:/usr/local/go/bin
```

Note: Changes made to a profile file may not apply until the next time you log into your computer. To apply the changes immediately, just run the shell commands directly or execute them from the profile using a command such as source $HOME/.profile.

Verify that you've installed Go by opening a command prompt and typing the following command:

```bash
go version
```
Confirm that the command prints the installed version of Go.

---
### Restic installation

[Restic](https://restic.readthedocs.io) is a backup program that is fast, efficient and secure. It supports the three major operating systems (Linux, macOS, Windows) and a few smaller ones (FreeBSD, OpenBSD).

#### Ubuntu
On Debian, there’s a package called restic which can be installed from the official repos, e.g. with apt-get:
```bash
apt-get install restic
```
#### From Source
In order to build restic from source, execute the following steps:

```bash
git clone https://github.com/restic/restic
cd restic
go run build.go
```
if you have problems reaching go modules and repositories try:

```bash
GOPROXY=direct
go run build.go
```
or 

```bash
go env -w GO111MODULE=on
go env -w GOPROXY=https://goproxy.cn,direct
```

For other installation guides for Linux distributions or windows go [Restic installation](https://restic.readthedocs.io/en/latest/020_installation.html) 

## Basic installation

You can install this package via composer using:

```bash
composer require itop/laravel-restic
```

The package will automatically register its service provider.

To publish the config file to config/restic.php run:

```bash
php artisan vendor:publish --provider="Itop\Restic\ResticServiceProvider"
```

This is the default contents of the configuration:

## Configuring 

This is the default contents of the configuration:

```php
<?php
return [

    'restic_binary_path' => '/var/www/restic/restic', 

    'repositories' => [

        'local' => [
            'backup_paths' => [
                '/var/www/katip/server/storage/',
                '/var/www/katip/web/storage/'
            ],
            'exclude_files' => [
                'vendor'
            ],
            'env' => [
                'RESTIC_PASSWORD'   => 'password',
                'RESTIC_REPOSITORY' => '/var/www/backup',
            ]
        ],

        'remote'  => [
            'backup_paths' => [
                '/var/www/katip/server/storage/',
                '/var/www/katip/web/storage/'
            ],
            'exclude_files' => [
                'vendor'
            ],
            'env' => [
                'RESTIC_PASSWORD'   => 'password',
                'RESTIC_REPOSITORY' => 'rest:https://user:password@host:port/user/',
            ]
        ],
    ],
];
```
For pathing options to restic use env section in config file.
Full environment variables that using restic listed [here](https://restic.readthedocs.io/en/latest/040_backup.html#environment-variables)

## Commands

### Init

Before you start to backup your files you have to init your repositories specified in config/restic.php.
The place where your backups will be saved is called a “repository”. 
All repositories specified in config/resic.php that you want to backup should be initiated separately

```bash
php artisan restic:init local # or remote
```

### Backup
Now we’re ready to backup some data. 
The contents of a directory at a specific point in time is called a “snapshot” in restic.
Run the following command for creating first snapshot.

```bash
php artisan restic:backup local
```

If you run the backup command again, restic will create another snapshot of your data, but this time it’s even faster and no new data was added to the repository (since all data is already there). This is de-duplication at work!

for exclude files from backup you can path other options like:

```bash
php artisan restic:backup local --exclude="*.c" --exclude-file=excludes.txt
```
if --exclude option is not passed will be used specified in config/restic.php or in env section

### Snapshots

Now, you can list all the snapshots stored in the repository:

```bash
php artisan restic:snapshots local
```

### Forget

In order to remove the snapshots of local repository use the forget command and specify the snapshot ID on the command line:

```bash
php artisan restic:forget local forget bdbd3439
```

Removing snapshots manually is tedious and error-prone, therefore restic allows specifying which snapshots should be removed automatically according to a policy. You can specify how many hourly, daily, weekly, monthly and yearly snapshots to keep, any other snapshots are removed. 

```bash
php artisan restic:forget local --keep-last 4 --tag foo --tag bar 
```

### Prune

After removing a snapshot the data that was referenced by files in that snapshot is still stored in the repository. To cleanup unreferenced data, the prune command must be run:

```bash
php artisan restic:prune local
```

Every command also includes a "help" screen which displays and describes the command's available arguments and options. To view a help screen, precede the name of the command with help

```bash
php artisan restic:forget --help
```

## Scheduling

See example of scheduling in App/Console/Kernel.php

```php

$logPath = storage_path('logs/restic.log');

// Local repository
$schedule->command('restic:backup local')
->hourly()
->appendOutputTo($logPath);

$schedule->command('restic:forget local --keep-last=4')
->dailyAt('12:00')
->appendOutputTo($logPath);

// Remote repository
$schedule->command('restic:backup remote')
->hourly()
->appendOutputTo($logPath);

$schedule->command('restic:forget remote --keep-last=4')
->dailyAt('12:00')
->appendOutputTo($logPath);

```