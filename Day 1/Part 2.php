<?php
// Luke Sheppard
// Advent of Code 2019 - Day 1 - Part 2 - https://adventofcode.com/2019/day/1#part2

$input = file_get_contents(__DIR__ . '/input.csv');

$modules = explode(',', $input);

function fuelForMass(int $mass) {
    return floor($mass / 3) - 2;
}

$requiredFuel = 0;

foreach ($modules as $mass) {
    // Calculate the initial fuel needed for the module mass
    $moduleFuel = fuelForMass($mass);
    $requiredFuel += $moduleFuel;

    // Fuel adds mass so requires more fuel itself.
    // Negative fuel requirement is handled as 0 fuel required.
    while ($moduleFuel >= 0) {
        $moduleFuel = fuelForMass($moduleFuel);
        $requiredFuel += max(0, $moduleFuel);
    }
}

echo $requiredFuel . "\n";
