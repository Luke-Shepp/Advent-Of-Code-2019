<?php
// Luke Sheppard
// Advent of Code 2019 - Day 5 - Part 2 - https://adventofcode.com/2019/day/5#part2

class ParameterMode {
    public const IMMEDIATE = 1;
    public const POSITION  = 0;
}

class Opcode {
    public const ADD        = 1;
    public const MULTIPLY   = 2;
    public const INPUT      = 3;
    public const OUTPUT     = 4;
    public const JUMP_TRUE  = 5;
    public const JUMP_FALSE = 6;
    public const LESS_THAN  = 7;
    public const EQUALS     = 8;
    public const HALT       = 99;
}

$input = file_get_contents(__DIR__ . '/input.csv');

$opcodes = explode(',', $input);

for ($i = 0; $i < count($opcodes);) {
    // Leading 0's may be ommitted from the input. Add them back in here.
    $instruction = str_pad($opcodes[$i], 5, '0', STR_PAD_LEFT);

    $opcode = (int) substr($instruction, -2);
    $mode1  = (int) substr($instruction, -3, 1);
    $mode2  = (int) substr($instruction, -4, 1);
    $mode3  = (int) substr($instruction, -5, 1);

    switch ($opcode) {
        case Opcode::HALT:
            break 2;

        case Opcode::ADD:
            $param1 = ($mode1 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+1]] : $opcodes[$i+1]);
            $param2 = ($mode2 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+2]] : $opcodes[$i+2]);
            $opcodes[$opcodes[$i+3]] = $param1 + $param2;
            $i += 4;
            continue 2;

        case Opcode::MULTIPLY:
            $param1 = ($mode1 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+1]] : $opcodes[$i+1]);
            $param2 = ($mode2 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+2]] : $opcodes[$i+2]);
            $opcodes[$opcodes[$i+3]] = $param1 * $param2;
            $i += 4;
            continue 2;

        case Opcode::INPUT:
            $input = readline("Input: ");
            $opcodes[$opcodes[$i+1]] = $input;
            $i += 2;
            continue 2;

        case Opcode::OUTPUT:
            echo "Output: " . ($mode1 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+1]] : $opcodes[$i+1]) . "\n";
            $i += 2;
            continue 2;

        case Opcode::JUMP_TRUE:
            $param1 = ($mode1 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+1]] : $opcodes[$i+1]);
            $param2 = ($mode2 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+2]] : $opcodes[$i+2]);
            if ($param1 != 0) {
                $i = $param2;
                continue 2;
            }
            $i += 3;
            continue 2;

        case Opcode::JUMP_FALSE:
            $param1 = ($mode1 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+1]] : $opcodes[$i+1]);
            $param2 = ($mode2 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+2]] : $opcodes[$i+2]);
            if ($param1 == 0) {
                $i = $param2;
                continue 2;
            }
            $i += 3;
            continue 2;

        case Opcode::LESS_THAN:
            $param1 = ($mode1 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+1]] : $opcodes[$i+1]);
            $param2 = ($mode2 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+2]] : $opcodes[$i+2]);
            $opcodes[$opcodes[$i+3]] = ($param1 < $param2 ? 1 : 0);
            $i += 4;
            continue 2;

        case Opcode::EQUALS:
            $param1 = ($mode1 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+1]] : $opcodes[$i+1]);
            $param2 = ($mode2 == ParameterMode::POSITION ? $opcodes[$opcodes[$i+2]] : $opcodes[$i+2]);
            $opcodes[$opcodes[$i+3]] = ($param1 == $param2 ? 1 : 0);
            $i += 4;
            continue 2;
    }

    // Unknown opcode - skip ahead.
    $i++;
}
