<?php
// Luke Sheppard
// Advent of Code 2019 - Day 14 - Part 2 - https://adventofcode.com/2019/day/14#part2

class Reaction {
    public $inputs = [];
    public $quantity = 0;
    public $name = '';
}

$input = file_get_contents(__DIR__ . '/input.txt');

$lines = explode("\n", $input);
$reactions = [];

foreach ($lines as $line) {
    $reaction = new Reaction;

    [$inputs, $output] = explode(' => ', $line);

    $reaction->inputs = array_reduce(explode(', ', $inputs), function ($result, $item) {
        [$qty, $name] = explode(' ', $item);
        $result[$name] = $qty;
        return $result;
    }, []);

    [$reaction->quantity, $reaction->name] = explode(' ', $output);

    $reactions[] = $reaction;
}

function find_reaction($reactions, $output) {
    return $reactions[array_search($output, array_column($reactions, 'name'))];
}

function getOre($reaction, &$leftOvers, $qtyWanted) {
    global $reactions;

    $ore = 0;
    $qtyNeeded = $qtyWanted;

    $producesQty  = $reaction->quantity;
    $producesName = $reaction->name;

    if (!empty($leftOvers[$producesName])) {
        if($leftOvers[$producesName] > $qtyWanted) {
            $leftOvers[$producesName] -= $qtyWanted;
            $qtyNeeded = 0;
        } else {
            $qtyWanted -= $leftOvers[$producesName];
            $leftOvers[$producesName] = 0;
            $qtyNeeded = $qtyWanted;
        }
    }

    if($qtyNeeded == 0){
        return 0;
    }

    $q = ceil($qtyNeeded / $producesQty);

    $leftover = ($q * $producesQty) - $qtyNeeded;

    if ($leftover > 0) {
        $leftOvers[$producesName] = ($leftOvers[$producesName] ?? 0) + $leftover;
    }

    foreach ($reaction->inputs as $inputName => $inputQty) {
        if ($inputName == 'ORE') {
            $ore +=  $inputQty * $q;
        } else {
            $el = find_reaction($reactions, $inputName);
            $ore += getOre($el, $leftOvers, $inputQty * $q);
        }
    }

    return $ore;

}

$leftovers = [];

$fuelReaction = find_reaction($reactions, 'FUEL');
$orePerFuel   = getOre($fuelReaction, $leftOvers, 1);
$oreAvailable = 1000000000000;

// Amount of ore needed per 1 fuel given the previous calculation
$newEstimate = floor($oreAvailable / $orePerFuel);

// Each iteration leaves us with some leftover chemicals which can be
// used on the next iteration to reduce consumption.
// Calculation credit https://dev.to/maxart2501/comment/ipmn
$estimate = null;
do {
  $estimate = $newEstimate;
  $newEstimate = floor($estimate * $oreAvailable / getOre($fuelReaction, $leftOvers, $estimate));
} while ($newEstimate > $estimate);

echo "Answer: " . $estimate . "\n";
