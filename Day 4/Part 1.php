<?php
// Luke Sheppard
// Advent of Code 2019 - Day 4 - Part 1 - https://adventofcode.com/2019/day/4

$inputFrom = 147981;
$inputTo   = 691423;

$cnt = 0;

foreach (range($inputFrom, $inputTo) as $num) {
    $hasDouble   = false;
    $consecutive = true;

    for ($i = 1; $i < strlen($num); $i++) {
        $curr = ((string) $num)[$i];
        $last = ((string) $num)[$i - 1];

        if ($curr == $last) $hasDouble = true;
        if ($curr < $last) $consecutive = false;
    }

    if ($hasDouble && $consecutive) $cnt++;
}

echo "Answer: " . $cnt . "\n";
