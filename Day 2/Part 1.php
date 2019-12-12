<?php
// Luke Sheppard
// Advent of Code 2019 - Day 2 - Part 1 - https://adventofcode.com/2019/day/2

$input = file_get_contents(__DIR__ . '/input.csv');

$opcodes = explode(',', $input);

for ($i = 0; $i < count($opcodes); $i += 4) {
    $opcode = $opcodes[$i];
    $params = array_slice($opcodes, $i + 1, 3);

    switch($opcode) {
        case 99: // Halt
            break 2;

        case 1: // Add
            $opcodes[$params[2]] = $opcodes[$params[0]] + $opcodes[$params[1]];
            break;

        case 2: // Multiply
            $opcodes[$params[2]] = $opcodes[$params[0]] * $opcodes[$params[1]];
            break;
    }
}

echo "Answer: " . $opcodes[0] . "\n";
