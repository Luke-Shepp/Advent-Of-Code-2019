<?php

class ParameterMode
{
    public const POSITION  = 0;
    public const IMMEDIATE = 1;
    public const RELATIVE  = 2;
}

class Opcode
{
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

class Computer
{
    /** @var array */
    private $opcodes;
    private $inputQueue  = [];
    private $outputQueue = [];

    /** @var int */
    private $relativeBase = 0;
    private $pointer = 0;

    /**
     * Initialise computer.
     *
     * @param string $programFile
     */
    public function __construct(string $programFile = 'input.csv')
    {
        $this->loadProgram($programFile);
    }

    /**
     * Loads an input file and processes it into opcodes
     *
     * @param string $programFile
     */
    private function loadProgram(string $programFile)
    {
        $input = file_get_contents(__DIR__ . '/' . $programFile);
        $this->opcodes = explode(',', $input);
    }

    /**
     * Add a value to the input queue
     *
     * @param int $value
     */
    public function addInput(int $value)
    {
        $this->inputQueue[] = $value;
    }

    /**
     * Fetch (and clear) the output queue contents
     *
     * @return array
     */
    public function getOutput(): array
    {
        $output = $this->outputQueue;
        $this->outputQueue = [];

        return $output;
    }

    /**
     * Get length of the output queue
     *
     * @return int
     */
    public function outputSize(): int
    {
        return count($this->outputQueue);
    }

    /**
     * Get the value for the given instructions parameter
     *
     * @param  string $instruction Current instruction. String due to being 0 padded.
     * @param  int $parameter
     * @return int
     */
    private function parameterValue(string $instruction, int $parameter): int
    {
        $address = $this->parameterAddress($instruction, $parameter);

        return $this->readMemory($address);
    }

    /**
     * Get the address / location for the given instructions parameter
     *
     * @param  string $instruction Current instruction. String due to being 0 padded.
     * @param  int $parameter
     * @throws \Exception
     * @return int
     */
    private function parameterAddress(string $instruction, int $parameter): int
    {
        $mode = (int) substr($instruction, -2 - $parameter, 1);

        switch ($mode) {
            case ParameterMode::POSITION:
                return $this->readMemory($this->pointer + $parameter);
            case ParameterMode::IMMEDIATE:
                return $this->pointer + $parameter;
            case ParameterMode::RELATIVE:
                return $this->relativeBase + $this->readMemory($this->pointer + $parameter);
            default:
                throw new \Exception("Unknown Parameter Mode: $mode");
        }
    }

    /**
     * Reads a memory location, increasing memory if the request location is out
     * of bounds.
     *
     * @param  int $location
     * @return int
     */
    private function readMemory(int $location): int
    {
        if (!isset($this->opcodes[$location])) {
            $this->increaseMemory($location + 1);
        }

        return $this->opcodes[$location];
    }

    /**
     * Writes a value to a specific memory location, increasing memory if the
     * requested location is out of bounds
     *
     * @param int $location
     * @param int $value
     */
    private function writeMemory(int $location, int $value)
    {
        if (!isset($this->opcodes[$location])) {
            $this->increaseMemory($location + 1);
        }

        $this->opcodes[$location] = $value;
    }

    /**
     * Increases memory to the given size (if larger than current memory size)
     *
     * @param int $size
     */
    private function increaseMemory(int $size)
    {
        if ($size > count($this->opcodes)) {
            for ($i = count($this->opcodes); $i < $size + 1; $i++) {
                $this->opcodes[$i] = 0;
            }
        }
    }

    /**
     * Run the program
     */
    public function run()
    {
        $this->pointer = 0;

        while ($this->pointer < count($this->opcodes)) {
            $exitCode = $this->step();

            if ($exitCode == 1) {
                break;
            }
        }
    }

    /**
     * Run the current instruction and move the pointer to the next instruction.
     *
     * @throws \Exception
     */
    public function step()
    {
        if ($this->pointer >= count($this->opcodes)) {
            return 1;
        }

        // Leading 0's may be ommitted from the input. Add them back in here.
        $instruction = str_pad($this->readMemory($this->pointer), 5, '0', STR_PAD_LEFT);

        $opcode = (int) substr($instruction, -2);

        switch ($opcode) {
            case Opcode::HALT:
                return 1;

            case Opcode::ADD:
                $param1 = $this->parameterValue($instruction, 1);
                $param2 = $this->parameterValue($instruction, 2);
                $targetAddress = $this->parameterAddress($instruction, 3);
                $this->writeMemory($targetAddress, $param1 + $param2);
                $this->pointer += 4;
                return 0;

            case Opcode::MULTIPLY:
                $param1 = $this->parameterValue($instruction, 1);
                $param2 = $this->parameterValue($instruction, 2);
                $targetAddress = $this->parameterAddress($instruction, 3);
                $this->writeMemory($targetAddress, $param1 * $param2);
                $this->pointer += 4;
                return 0;

            case Opcode::INPUT:
                if (empty($this->inputQueue)) {
                    throw new \Exception("Expecting input, but input queue is empty.");
                }

                $input = array_shift($this->inputQueue);
                $targetAddress = $this->parameterAddress($instruction, 1);
                $this->writeMemory($targetAddress, $input);
                $this->pointer += 2;
                return 0;

            case Opcode::OUTPUT:
                $this->outputQueue[] =  $this->parameterValue($instruction, 1);
                $this->pointer += 2;
                return 0;

            case Opcode::JUMP_TRUE:
                $param1 = $this->parameterValue($instruction, 1);
                $param2 = $this->parameterValue($instruction, 2);
                if ($param1 != 0) {
                    $this->pointer = $param2;
                    return 0;
                }
                $this->pointer += 3;
                return 0;

            case Opcode::JUMP_FALSE:
                $param1 = $this->parameterValue($instruction, 1);
                $param2 = $this->parameterValue($instruction, 2);
                if ($param1 == 0) {
                    $this->pointer = $param2;
                    return 0;
                }
                $this->pointer += 3;
                return 0;

            case Opcode::LESS_THAN:
                $param1 = $this->parameterValue($instruction, 1);
                $param2 = $this->parameterValue($instruction, 2);
                $targetAddress = $this->parameterAddress($instruction, 3);
                $this->writeMemory($targetAddress, ($param1 < $param2 ? 1 : 0));
                $this->pointer += 4;
                return 0;

            case Opcode::EQUALS:
                $param1 = $this->parameterValue($instruction, 1);
                $param2 = $this->parameterValue($instruction, 2);
                $targetAddress = $this->parameterAddress($instruction, 3);
                $this->writeMemory($targetAddress, ($param1 == $param2 ? 1 : 0));
                $this->pointer += 4;
                return 0;

            case Opcode::ADJUST_BASE:
                $param1 = $this->parameterValue($instruction, 1);
                $this->relativeBase += $param1;
                $this->pointer += 2;
                return 0;
        }

        // Unknown opcode - skip ahead.
        $this->pointer++;
    }
}
