<?php

namespace Itop\Restic\Restic;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use Exception;

class Restic
{
    /** @var array */
    protected $env;

    /** @var array */
    protected $command = [];

    /** @var int */
    protected $timeout = 3600;

    /** @var Symfony\Component\Process\Process */
    protected $proccess;

    public function addCommand($command) : self
    {
        $this->merge([$command]);
        return $this;
    }

    public function getCommand() : array
    {
        return $this->command;
    }

    public function setTimeout(int $time) : self
    {
        $this->timeout = $time;
        return $this;
    }

    public function setEnvironment(array $env) : self
    {
        foreach ($env as $key => $v) {
            $this->env[strtoupper($key)] = is_array($v) ? implode(' ', $v) : $v;
        }
        return $this;
    }

    public function shouldBackup(array $bpaths) : self
    {
        $this->merge(['backup']);
        $this->merge($bpaths);
        return $this;
    }

    public function excludeFilesFrom(array $files) : self
    {
        $files = implode(' ', $files);
        $this->push("--exclude={$files}");
        return $this;
    }

    public function run(): self
    {
        $this->process = new Process($this->command);

        $this->process->setTimeout($this->timeout);
       
        $this->process->mustRun(null, $this->env);

        return $this;
    }

    public function getProcessOutput()
    {
        return $this->process->getOutput();
    }

    protected function merge($command): void
    {
        $this->command = array_merge($this->command, $command);
    }

    protected function push($command): void
    {
        array_push($this->command, $command);
    }
}
