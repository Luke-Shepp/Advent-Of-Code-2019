<?php
// Luke Sheppard
// Advent of Code 2019 - Day 13 - Part 2 - https://adventofcode.com/2019/day/13#part2

require_once __DIR__ . '/Computer.php';

class TileType {
    public const EMPTY  = 0;
    public const WALL   = 1;
    public const BLOCK  = 2;
    public const PADDLE = 3;
    public const BALL   = 4;
}

$c = new Computer();

$screen = [];
$score  = 0;

// Play for free!
$c->writeMemory(0, 2);

do {
    $exitCode = 0;

    try {
        $exitCode = $c->step();
    } catch (ExpectsInputException $ex) {
        draw($screen, $score); // Comment out to speed up. Or leave and watch the game!
        $c->addInput(get_input($screen));
        $exitCode = $c->step();
    }

    // We need 3 output values to be able to process
    if ($c->outputSize() == 3) {
        [$x, $y, $tileId] = $c->getOutput();

        // An output of [-1, 0, *] is a new score, not a tile value.
        if ($x == -1 && $y == 0) {
            $score = $tileId;
        } else {
            $screen[$x][$y] = $tileId;
        }
    }

    if ($exitCode == 1) {
        break;
    }
} while (true);

system('clear');

echo "You won! Final score: $score\n";

function draw($screen, $score) {
    system('clear');

    echo "Score: $score\n\n";

    for ($y = 0; $y < 24; $y++) {
        for ($x = 0; $x < 42; $x++) {
            switch ($screen[$x][$y] ?? TileType::EMPTY) {
                case TileType::WALL:
                    echo '█';
                    break;

                case TileType::BLOCK:
                    echo '▒';
                    break;

                case TileType::PADDLE:
                    echo '═';
                    break;

                case TileType::BALL:
                    echo '●';
                    break;

                default:
                case TileType::EMPTY:
                    echo '·';
                    break;
            }
        }
        echo "\n";
    }
}

function get_input($screen) {
    $ball = [];
    $paddle = [];

    // Search for the position of the ball and paddle
    for ($y = 0; $y < 24; $y++) {
        for ($x = 0; $x < 42; $x++) {
            $tileType = $screen[$x][$y] ?? TileType::EMPTY;
            if ($tileType == TileType::BALL) {
                $ball = [$x, $y];
                break 2;
            }
        }
    }

    // The paddle is always going to be in the bottom row. If we fetch both
    // positions in one loop and the ball is near the top, it would cause the
    // loop to continue right down to the paddle on the bottom row. We can speed
    // it up by just searching for the paddle on the row it will always be on!
    $y = 23;
    for ($x = 0; $x < 42; $x++) {
        $tileType = $screen[$x][$y] ?? TileType::EMPTY;
        if ($tileType == TileType::PADDLE) {
            $paddle = [$x, $y];
            break;
        }
    }

    // Compare the ball X to the paddle X coord to get the next input.
    // -1 = left
    // 0 = stay
    // 1 = right
    return $ball[0] <=> $paddle[0];
}
