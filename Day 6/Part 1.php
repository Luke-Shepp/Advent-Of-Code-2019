<?php
// Luke Sheppard
// Advent of Code 2019 - Day 6 - Part 1 - https://adventofcode.com/2019/day/6

$input = file_get_contents(__DIR__ . '/input.txt');

$rawOrbits = explode("\n", $input);

$orbits = [];

foreach ($rawOrbits as $orbit) {
    $description = explode(')', $orbit);
    $orbits[$description[1]] = $description[0];
}

function count_orbits($parent, $orbits) {
    $count = 0;

    if (isset($orbits[$parent])) {
        $count++;
        $count += count_orbits($orbits[$parent], $orbits);
    }

    return $count;
}

$count = 0;

foreach (array_keys($orbits) as $parent) {
    $count += count_orbits($parent, $orbits);
}

echo "Answer: " . $count . "\n";
