<?php
// Luke Sheppard
// Advent of Code 2019 - Day 12 - Part 2 - https://adventofcode.com/2019/day/12#part2

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

$startingState = $moons;
$startingVel = $velocities;

$moves = 0;

$minDimIterations = [0, 0, 0];

do {
    apply_gravity($moons, $velocities);
    apply_velocity($moons, $velocities);

    $moves++;

    // Detect if any of the dimensions (x, y, z) positions and velocities
    // match that of their starting values. If so, save the number of moves
    // taken to get here for use calculating the LCM.
    for ($d = 0; $d < 3; $d++) {
        $dimMatchesInitial = true;
        for ($i = 0; $i < 4; $i++) {
            if ($moons[$i][$d] != $startingState[$i][$d]
              || $velocities[$i][$d] != $startingVel[$i][$d]) {
                $dimMatchesInitial = false;
            }
        }
        if ($dimMatchesInitial && $minDimIterations[$d] == 0) {
            $minDimIterations[$d] = $moves;
        }
    }

} while ($minDimIterations[0] == 0 || $minDimIterations[1] == 0 || $minDimIterations[2] == 0);


echo "Answer: " . calc_lcm(...$minDimIterations) . "\n";

/**
 * Calculate the lowest common multiple using the greatest common divisor
 */
function calc_lcm($a, $b, $c = null) {
    if (!is_null($c)) {
        return calc_lcm($c, calc_lcm($a, $b));
    }
    return ($a * $b) / calc_gcd($a, $b);
}

/**
 * Euclidean algorithm
 */
function calc_gcd($a, $b) {
    while ($b != 0) {
        $tmp = $b;
        $b = $a % $b;
        $a = $tmp;
    }

    return $a;
}

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
