<?php

require_once __DIR__ . '/Computer.php';

$c = new Computer();

// Commands found from manually running through directions first.
$commands = [
    'north', 'west', 'east', 'east', 'take coin', 'east', 'north', 'south',
    'west', 'west', 'south', 'south', 'take food ration', 'west', 'take sand',
    'north', 'north', 'east', 'take astrolabe', 'west', 'south', 'south',
    'east', 'north', 'east', 'take cake', 'south', 'take weather machine',
    'west', 'take ornament', 'west', 'take jam', 'east', 'east', 'east', 'west',
    'north', 'east', 'east', 'east',

    // Drop items which are too heavy when held on their own.
    'drop sand', 'drop cake', 'drop coin',
];

// Run through inital commands to collect items and get to the security checkpoint
foreach ($commands as $command) {
    foreach (str_split($command) as $chr) {
        $c->addInput(ord($chr));
    }
    $c->addInput(10);
}

$inventory = ['jam', 'astrolabe', 'ornament', 'weather machine', 'food ration'];

$itemCombinations = [[]];

foreach ($inventory as $element) {
    foreach ($itemCombinations as $combination) {
        $itemCombinations[] = array_merge(array($element), $combination);
    }
}

// Drop everything to start with.
foreach ($inventory as $item) {
    $c->addInputString('drop ' . $item);
}

// Add each item from the combo, attempt to go south, and drop everything if unsuccessful.
foreach ($itemCombinations as $combo) {
    foreach ($combo as $item) {
        $c->addInputString('take ' . $item);
    }
    $c->addInputString('south');
    foreach ($combo as $item) {
        $c->addInputString('drop ' . $item);
    }
}

do {
    try {
        $exitCode = $c->step();

        foreach ($c->getOutput() as $o) {
            echo chr($o);
        }

        if ($exitCode == 1) {
            break;
        }

    } catch (ExpectsInputException $e) {
        // Fallback to manual input if initial commands have all finished.
        $input = readline();
        foreach (str_split($input) as $chr) {
            $c->addInput(ord($chr));
        }
        $c->addInput(10);
    }
} while(true);
