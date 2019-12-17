<?php
// Luke Sheppard
// Advent of Code 2019 - Day 11 - Part 1 & 2 - https://adventofcode.com/2019/day/11

require_once __DIR__ . '/Computer.php';

class Colour {
    public const BLACK = 0;
    public const WHITE = 1;
}

class Rotation {
    public const UP    = 0;
    public const RIGHT = 90;
    public const DOWN  = 180;
    public const LEFT  = 270;
}

class Turn {
    public const ANTI_CLOCK = 0;
    public const CLOCK      = 1;
}

$options = getopt('', ['part:']);

if (empty($options['part'])) {
    echo "Please specify either --part=1 or --part=2\n";
    die();
}

$c = new Computer();

$grid = [];

// x, y, rotation
$position = [0, 0, Rotation::UP];

$moves = [
    Rotation::UP    => [-1, 0],
    Rotation::DOWN  => [1, 0],
    Rotation::LEFT  => [0, -1],
    Rotation::RIGHT => [0, 1],
];

$panelsPainted = 0;

// Set the intial panel colour.
if ($options['part'] == 1) {
    $c->addInput(Colour::BLACK);
} elseif ($options['part'] == 2) {
    $c->addInput(Colour::WHITE);
}

do {
    $exitCode = $c->step();

    if ($exitCode == 1) {
        break;
    }

    // Wait until there's an output of 2 items. (colour and next rotation)
    if ($c->outputSize() == 2) {
        [$colour, $turn] = $c->getOutput();

        if (!isset($grid[$position[0]][$position[1]])) {
            // This panel hasn't been painted yet - increment the panels painted count
            $panelsPainted++;
        }

        $grid[$position[0]][$position[1]] = $colour;

        if ($turn == Turn::CLOCK) {
            $position[2] += 90;
        } elseif ($turn == Turn::ANTI_CLOCK) {
            $position[2] -= 90;
        }

        // Limit rotation to be between 0° and 360°.
        if ($position[2] >= 360) {
            $position[2] = $position[2] % 360;
        } elseif ($position[2] < 0) {
            $position[2] = 360 + $position[2];
        }

        // Move forward in the current direction.
        $position[0] += $moves[$position[2]][0];
        $position[1] += $moves[$position[2]][1];

        // Pass the input of the current panel colour
        $c->addInput($grid[$position[0]][$position[1]] ?? Colour::BLACK);
    }
} while (true);


if ($options['part'] == 1) {
    echo "Answer: " . $panelsPainted . "\n";
} elseif ($options['part'] == 2) {
    for ($x = 0; $x < 7; $x++) {
        for ($y = 0; $y < 40; $y++) {
            $colour = $grid[$x][$y] ?? Colour::BLACK;
            if ($colour == Colour::BLACK) {
                echo " ";
            } else {
                echo "█";
            }
        }
        echo "\n";
    }
}
