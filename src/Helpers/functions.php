<?php

use Itop\Restic\Helpers\ConsoleOutput;

function consoleOutput(): ConsoleOutput
{
    return app(ConsoleOutput::class);
}

function stringify(array $array)
{
    $string = '';

    foreach ($array as $a) {
        $string .= $a;
    }

    return $string;
}
