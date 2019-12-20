<?php
// Luke Sheppard
// Advent of Code 2019 - Day 7 - Part 2 - https://adventofcode.com/2019/day/7#part2

require_once __DIR__ . '/Computer.php';

$largestOutputSignal = 0;

$phaseSettings = pc_permute([5, 6, 7, 8, 9]);

foreach ($phaseSettings as $phaseSetting) {
    $amps = [new Computer(), new Computer(), new Computer(), new Computer(), new Computer()];

    foreach ($phaseSetting as $i => $setting) {
        $amps[$i]->addInput($setting);
    }

    $ampNum = 0;

    $output = 0;

    do {
        $amps[$ampNum]->addInput($output);

        // Step only until there is output (or the program is halted)
        while ($amps[$ampNum]->outputSize() == 0) {
            $exitCode = $amps[$ampNum]->step();

            if ($exitCode == 1) break;
        }

        // If there's no output for this amp, it means it's just finished -
        // so skip the rest.
        if ($amps[$ampNum]->outputSize() == 0) {
            break;
        }

        // Collect the output from this amp ready to pass to the next as an input.
        [$output] = $amps[$ampNum]->getOutput();

        $ampNum++;

        // Make sure output from the last amp loops back to the first amp.
        if ($ampNum >= count($amps)) {
            $ampNum = $ampNum % count($amps);
        }

    } while(true);

    if ($output > $largestOutputSignal) {
        $largestOutputSignal = $output;
    }
}

echo "Answer: $largestOutputSignal\n";


function pc_permute($items, $perms = []) {
    if (empty($items)) {
        $return = array($perms);
    }  else {
        $return = array();
        for ($i = count($items) - 1; $i >= 0; --$i) {
             $newitems = $items;
             $newperms = $perms;
             list($foo) = array_splice($newitems, $i, 1);
             array_unshift($newperms, $foo);
             $return = array_merge($return, pc_permute($newitems, $newperms));
         }
    }
    return $return;
}
