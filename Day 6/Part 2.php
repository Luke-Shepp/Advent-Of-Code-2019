<?php
// Luke Sheppard
// Advent of Code 2019 - Day 6 - Part 2 - https://adventofcode.com/2019/day/6#part2

$input = file_get_contents(__DIR__ . '/input.txt');

$rawOrbits = explode("\n", $input);

$orbits = [];

foreach ($rawOrbits as $orbit) {
    $description = explode(')', $orbit);
    $orbits[$description[1]] = $description[0];
}

function orbit_path($from, $orbits) {
    $path = [];

    if (isset($orbits[$orbits[$from]])) {
        $path[] = $orbits[$from];
        $path = array_merge($path, orbit_path($orbits[$from], $orbits));
    }

    return $path;
}

$yourOrbitPath   = orbit_path('YOU', $orbits);
$santasOrbitPath = orbit_path('SAN', $orbits);

$steps = 0;

// Find the first common path between the two orbits, and calculate steps travelled.
for ($i = 0; $i < count($yourOrbitPath); $i++) {
    for ($j = 0; $j < count($santasOrbitPath); $j++){
        if ($yourOrbitPath[$i] == $santasOrbitPath[$j]) {
            $steps = $i + $j;
            break 2;
        }
    }
}

echo "Answer: " . $steps . "\n";
