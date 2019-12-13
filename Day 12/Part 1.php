<?php
// Luke Sheppard
// Advent of Code 2019 - Day 12 - Part 1 - https://adventofcode.com/2019/day/12

$input = file_get_contents(__DIR__ . '/input.txt');

$lines = explode("\n", $input);

$moons = [];

// Extract coord digits from the input.
foreach ($lines as $line) {
    $digits = [];
    preg_match_all('/\-?\d+/', $line, $digits);
    $moons = array_merge($moons, $digits);
}

$velocities = [[0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0]];

for ($i = 0; $i < 1000; $i++) {
    apply_gravity($moons, $velocities);
    apply_velocity($moons, $velocities);
}

echo "Answer: " . calculate_energy($moons, $velocities) . "\n";

/**
 * "To apply gravity, consider every pair of moons. On each axis (x, y, and z),
 * the velocity of each moon changes by exactly +1 or -1 to pull the moons
 * together. For example, if Ganymede has an x position of 3, and Callisto
 * has a x position of 5, then Ganymede's x velocity changes by +1
 * (because 5 > 3) and Callisto's x velocity changes by -1 (because 3 < 5).
 * However, if the positions on a given axis are the same, the velocity on
 * that axis does not change for that pair of moons."
 */
function apply_gravity($moons, &$velocities) {
    for ($i = 0; $i < 3; $i++) {
        for ($j = $i + 1; $j < 4; $j++) {
            for ($d = 0; $d < 3; $d++) {
                if ($moons[$i][$d] > $moons[$j][$d]) {
                    $velocities[$i][$d] -= 1;
                    $velocities[$j][$d] += 1;
                } elseif ($moons[$i][$d] < $moons[$j][$d]) {
                    $velocities[$i][$d] += 1;
                    $velocities[$j][$d] -= 1;
                }
            }
        }
    }
}

/**
 * "Apply velocity: simply add the velocity of each moon to its own position."
 */
function apply_velocity(&$moons, $velocities) {
    for ($i = 0; $i < 4; $i++) {
        for ($d = 0; $d < 3; $d++) {
            $moons[$i][$d] += $velocities[$i][$d];
        }
    }
}

/**
 * "The total energy for a single moon is its potential energy multiplied by
 * its kinetic energy. A moon's potential energy is the sum of the absolute
 * values of its x, y, and z position coordinates. A moon's kinetic energy is
 * the sum of the absolute values of its velocity coordinates."
 */
function calculate_energy($moons, $velocities) {
    $energy = 0;

    foreach ($moons as $i => $moon) {
        $kinetic   = array_sum(array_map('abs', $velocities[$i]));
        $potential = array_sum(array_map('abs', $moon));

        $energy += $potential * $kinetic;
    }

    return $energy;
}
