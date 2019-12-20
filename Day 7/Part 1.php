<?php
// Luke Sheppard
// Advent of Code 2019 - Day 7 - Part 1 - https://adventofcode.com/2019/day/7

require_once __DIR__ . '/Computer.php';

$largestOutputSignal = 0;

$phaseSettings = pc_permute([0, 1, 2, 3, 4]);

foreach ($phaseSettings as $phaseSetting) {
    $ampInput = 0;

    for ($i = 0; $i < count($phaseSetting); $i++) {
        $c = new Computer();

        $c->addInput($phaseSetting[$i]);
        $c->addInput($ampInput);

        $c->run();

        [$ampInput] = $c->getOutput();
    }

    if ($ampInput > $largestOutputSignal) {
        $largestOutputSignal = $ampInput;
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
