<?php
// Luke Sheppard
// Advent of Code 2019 - Day 2 - Part 2 - https://adventofcode.com/2019/day/2#part2

$input = file_get_contents(__DIR__ . '/input.csv');

$target = 19690720;

for ($noun = 0; $noun < 100; $noun++) {
    for ($verb = 0; $verb < 100; $verb++) {
        $opcodes = explode(',', $input);
        $opcodes[1] = $noun;
        $opcodes[2] = $verb;

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

        if ($opcodes[0] == $target) {
            break 2;
        }
    }
}


echo "Answer: " . (100 * $noun + $verb) .  "\n";
