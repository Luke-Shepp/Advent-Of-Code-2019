<?php
// Luke Sheppard
// Advent of Code 2019 - Day 9 - Part 1 & 2 - https://adventofcode.com/2019/day/9

class ParameterMode {
    public const POSITION  = 0;
    public const IMMEDIATE = 1;
    public const RELATIVE  = 2;
}

class Opcode {
    public const ADD         = 1;
    public const MULTIPLY    = 2;
    public const INPUT       = 3;
    public const OUTPUT      = 4;
    public const JUMP_TRUE   = 5;
    public const JUMP_FALSE  = 6;
    public const LESS_THAN   = 7;
    public const EQUALS      = 8;
    public const ADJUST_BASE = 9;
    public const HALT        = 99;
}

$input = file_get_contents(__DIR__ . '/input.csv');

$opcodes = explode(',', $input);

/**
 * Get the value for the given instructions parameter
 *
 * Wow. This took way too long to debug always getting a 203 output.
 * When writing to a memory location, a seperate method is needed to return
 * the address NOT the value.
 */
function get_parameter_value($instruction, $pointer, $parameter, $debug = false) {
    return read_memory(get_parameter_address($instruction, $pointer, $parameter));
}

/**
 * Get the address / location for the given instructions parameter
 */
function get_parameter_address($instruction, $pointer, $parameter) {
    global $opcodes, $relativeBase;

    $mode = (int) substr($instruction, -2 - $parameter, 1);

    switch ($mode) {
        case ParameterMode::POSITION:
            return read_memory($pointer + $parameter);
        case ParameterMode::IMMEDIATE:
            return $pointer + $parameter;
        case ParameterMode::RELATIVE:
            return $relativeBase + read_memory($pointer + $parameter);
        default:
            throw new \Exception("Unknown Parameter Mode: $mode");
    }
}

/**
 * Reads a memory location, increasing memory if the request location is out
 * of bounds.
 */
function read_memory($location) {
    global $opcodes;

    if (!isset($opcodes[$location])) {
        increase_memory($location + 1);
    }

    return $opcodes[$location];
}

/**
 * Writes a value to a specific memory location, increasing memory if the
 * requested location is out of bounds
 */
function write_memory($location, $value) {
    global $opcodes;

    if (!isset($opcodes[$location])) {
        increase_memory($location + 1);
    }

    $opcodes[$location] = $value;
}

/**
 * Increases memory to the given size (if larger than current memory size)
 */
function increase_memory($size) {
    global $opcodes;

    if ($size > count($opcodes)) {
        for ($i = count($opcodes); $i < $size + 1; $i++) {
            $opcodes[$i] = 0;
        }
    }
}


/// Main Program.


$relativeBase = 0;

for ($i = 0; $i < count($opcodes);) {
    // Leading 0's may be ommitted from the input. Add them back in here.
    $instruction = str_pad(read_memory($i), 5, '0', STR_PAD_LEFT);

    $opcode = (int) substr($instruction, -2);

    switch ($opcode) {
        case Opcode::HALT:
            break 2;

        case Opcode::ADD:
            $param1 = get_parameter_value($instruction, $i, 1);
            $param2 = get_parameter_value($instruction, $i, 2);
            write_memory(get_parameter_address($instruction, $i, 3), $param1 + $param2);
            $i += 4;
            continue 2;

        case Opcode::MULTIPLY:
            $param1 = get_parameter_value($instruction, $i, 1);
            $param2 = get_parameter_value($instruction, $i, 2);
            write_memory(get_parameter_address($instruction, $i, 3), $param1 * $param2);
            $i += 4;
            continue 2;

        case Opcode::INPUT:
            $input = readline("Input: ");
            write_memory(get_parameter_address($instruction, $i, 1), $input);
            $i += 2;
            continue 2;

        case Opcode::OUTPUT:
            echo "Output: " . get_parameter_value($instruction, $i, 1) . "\n";
            $i += 2;
            continue 2;

        case Opcode::JUMP_TRUE:
            $param1 = get_parameter_value($instruction, $i, 1);
            $param2 = get_parameter_value($instruction, $i, 2);
            if ($param1 != 0) {
                $i = $param2;
                continue 2;
            }
            $i += 3;
            continue 2;

        case Opcode::JUMP_FALSE:
            $param1 = get_parameter_value($instruction, $i, 1);
            $param2 = get_parameter_value($instruction, $i, 2);
            if ($param1 == 0) {
                $i = $param2;
                continue 2;
            }
            $i += 3;
            continue 2;

        case Opcode::LESS_THAN:
            $param1 = get_parameter_value($instruction, $i, 1);
            $param2 = get_parameter_value($instruction, $i, 2);
            write_memory(get_parameter_address($instruction, $i, 3), ($param1 < $param2 ? 1 : 0));
            $i += 4;
            continue 2;

        case Opcode::EQUALS:
            $param1 = get_parameter_value($instruction, $i, 1);
            $param2 = get_parameter_value($instruction, $i, 2);
            write_memory(get_parameter_address($instruction, $i, 3), ($param1 == $param2 ? 1 : 0));
            $i += 4;
            continue 2;

        case Opcode::ADJUST_BASE:
            $param1 = get_parameter_value($instruction, $i, 1);
            $relativeBase += $param1;
            $i += 2;
            continue 2;
    }

    // Unknown opcode - skip ahead.
    $i++;
}
