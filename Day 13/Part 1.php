<?php
// Luke Sheppard
// Advent of Code 2019 - Day 13 - Part 1 - https://adventofcode.com/2019/day/13

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

$blockTileCount = 0;

do {
    $exitCode = $c->step();

    if ($c->outputSize() == 3) {
        [$x, $y, $tileId] = $c->getOutput();

        if ($tileId == TileType::BLOCK) {
            $blockTileCount++;
        }

        $screen[$x][$y] = $tileId;
    }

    if ($exitCode == 1) {
        break;
    }
} while (true);

echo "Answer: $blockTileCount\n";
