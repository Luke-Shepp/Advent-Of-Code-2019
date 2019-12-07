<?php
// Luke Sheppard
// Advent of Code 2019 - Day 2 - Part 2 - https://adventofcode.com/2019/day/2#part2

$target = 19690720;

for ($noun = 0; $noun < 100; $noun++) {
    for ($verb = 0; $verb < 100; $verb++) {
        $opcodes = [
            1, $noun, $verb, 3, 1, 1, 2, 3, 1, 3, 4, 3, 1, 5, 0, 3, 2, 1, 10,
            19, 1, 19, 6, 23, 2, 23, 13, 27, 1, 27, 5, 31, 2, 31, 10, 35, 1,
            9, 35, 39, 1, 39, 9, 43, 2, 9, 43, 47, 1, 5, 47, 51, 2, 13, 51, 55,
            1, 55, 9, 59, 2, 6, 59, 63, 1, 63, 5, 67, 1, 10, 67, 71, 1, 71, 10,
            75, 2, 75, 13, 79, 2, 79, 13, 83, 1, 5, 83, 87, 1, 87, 6, 91, 2,
            91, 13, 95, 1, 5, 95, 99, 1, 99, 2, 103, 1, 103, 6, 0, 99, 2, 14, 0, 0
        ];

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
