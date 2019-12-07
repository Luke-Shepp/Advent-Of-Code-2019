<?php
// Luke Sheppard
// Advent of Code 2019 - Day 3 - Part 1 - https://adventofcode.com/2019/day/3

ini_set('memory_limit', '500M');

$input = file_get_contents(__DIR__ . '/input.csv');

$wires = explode("\n", $input, 2);

$matrix = [];

foreach ($wires as $wire) {
    $matrix[] = plot_wire($wire);
}

function plot_wire($paths) {
    $paths = explode(',', $paths);

    $actions = [
        'L' => [-1, 0],
        'R' => [1, 0],
        'D' => [0, -1],
        'U' => [0, 1]
    ];

    $matrix = [[0, 0]];

    foreach ($paths as $path) {
        $direction = $path[0];
        $distance = substr($path, 1);

        for ($i = 0; $i < $distance; $i++) {
            $last = end($matrix);
            $matrix[] = [$last[0] + $actions[$direction][0], $last[1] + $actions[$direction][1]];
        }
    }

    return $matrix;
}

$intersects = array_uintersect($matrix[0], $matrix[1], function ($a, $b) {
  return $a <=> $b;
});

$closest = null;

foreach ($intersects as $intersect) {
    $distance = (abs($intersect[0]) + abs($intersect[1]));
    if (($closest === null || $distance < $closest) && $distance > 0) {
        $closest = $distance;
    }
}

echo "Answer: " . $closest . "\n";
