<?php
// Luke Sheppard
// Advent of Code 2019 - Day 4 - Part 2 - https://adventofcode.com/2019/day/4#part2

$inputFrom = 147981;
$inputTo   = 691423;

$cnt = 0;

foreach (range($inputFrom, $inputTo) as $num) {
    $hasDouble   = false;
    $consecutive = true;

    for ($i = 1; $i < strlen($num); $i++) {
        $curr    = ((string) $num)[$i];
        $last    = ((string) $num)[$i - 1];
        $twoback = ((string) $num)[$i - 2] ?? null;
        $next    = ((string) $num)[$i + 1] ?? null;

        // Current number must equal the last number, however the number 2 back
        // and the next number cannot be equal to this.
        // Meaning to count as a double it MUST be 2 in a row - 3 in a row does not count.
        // On the last number there will be no "next" number - so it's just `null`.
        if ($curr == $last
            && ($twoback === null || $twoback != $curr)
            && ($next === null || $next != $curr)
        ) {
            $hasDouble = true;
        }

        if ($curr < $last) $consecutive = false;
    }

    if ($hasDouble && $consecutive) $cnt++;
}

echo "Answer: " . $cnt . "\n";
