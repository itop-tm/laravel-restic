<?php

use Itop\Restic\Helpers\ConsoleOutput;

function consoleOutput(): ConsoleOutput
{
    return app(ConsoleOutput::class);
}