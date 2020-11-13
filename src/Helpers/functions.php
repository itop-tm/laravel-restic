<?php

use Itop\Restic\Helpers\ConsoleOutput;

function consoleOutput(): ConsoleOutput
{
    return app(ConsoleOutput::class);
}

// function stringify(array $array)
// {
//     $string = '';

//     if (count($array) == 1) {
//         return $array[0];
//     }

//     foreach ($array as $key => $a) {
//         $string .= $key == 0 ? $a : " $a";
//     }

//     return $string;
// }
