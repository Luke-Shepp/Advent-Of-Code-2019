<?php

require_once __DIR__ . '/Computer.php';

$grid   = [];
$row    = [];
$facing = 'U';
$moves  = [];
$robotPosition = [];
$currentDirectionMoves = 0;

// $transformations[$currentDirection][$nextMove] = $resultingDirection
$transformations = [
    'U' => ['L' => 'L', 'R' => 'R'],
    'D' => ['L' => 'R', 'R' => 'L'],
    'L' => ['L' => 'D', 'R' => 'U'],
    'R' => ['L' => 'U', 'R' => 'D'],
];

$directions = [
    'U' => [-1, 0],
    'D' => [1,  0],
    'L' => [0, -1],
    'R' => [0,  1],
];

// Run through once to get the layout.
$c = new Computer();

$c->run();

foreach ($c->getOutput() as $val) {
    if ($val == 10) {
        $grid[] = $row;
        $row = [];
    } else {
        $row[] = chr($val);
        if (chr($val) == '^') {
            $robotPosition = [count($grid), count($row) - 1];
        }
    }
}

// Find the path from current location, to the end.
do {
    // Check forwards in the direction the robot is facing
    $forward = is_scaffold($robotPosition[0]+$directions[$facing][0], $robotPosition[1]+$directions[$facing][1]);

    if ($forward) {
        $currentDirectionMoves++;
        $robotPosition[0] += $directions[$facing][0];
        $robotPosition[1] += $directions[$facing][1];
        continue;
    }

    // Turn left, and check if there is scaffolding in that direction
    $facingIfRotateLeft = $transformations[$facing]['L'];
    $left = is_scaffold($robotPosition[0]+$directions[$facingIfRotateLeft][0], $robotPosition[1]+$directions[$facingIfRotateLeft][1]);

    // Turn right, and check if there is scaffolding in that direction
    $facingIfRotateRight = $transformations[$facing]['R'];
    $right = is_scaffold($robotPosition[0]+$directions[$facingIfRotateRight][0], $robotPosition[1]+$directions[$facingIfRotateRight][1]);

    // At this point, we can't go forward any more so add the forward step count
    // into the moves array.
    if ($currentDirectionMoves > 0) {
        $moves[] = $currentDirectionMoves;
        $currentDirectionMoves = 0;
    }

    if ($left) {
        $moves[] = 'L';
        $facing = $facingIfRotateLeft;
    } elseif ($right) {
        $moves[] = 'R';
        $facing = $facingIfRotateRight;
    } else {
        // We can't do anything. Could be at the end.
        break;
    }
} while(true);


function is_scaffold($row, $col) {
    global $grid;

    return (($grid[$row][$col] ?? '') == '#');
}


// Find patterns in movements and split into 3 "functions" (A/B/C)
$groups = [];

// Regex credit: https://www.reddit.com/r/adventofcode/comments/ebr7dg/2019_day_17_solutions/fb8wo98/
$moveString = implode(',', $moves) . ',';
preg_match('/^(.{1,20})\1*(.{1,20})(?:\1|\2)*(.{1,20})(?:\1|\2|\3)*$/', $moveString, $groups);

// Get the order to run the functions. The first is always going to be A
$builtString = $groups[1];
$functions   = 'A,';

do {
    for ($i = 1; $i < 4; $i++) {
        if ($builtString . $groups[$i] == substr($moveString, 0, strlen($builtString . $groups[$i]))) {
            $functions .= ($i == 1 ? 'A' : ($i == 2 ? 'B' : 'C')) . ',';
            $builtString .= $groups[$i];
        }
    }
} while ($builtString != $moveString);


///// Second run through to complete.

$c = new Computer();

// Wake up the vacuum robot!
$c->writeMemory(0, 2);

// Process function list and function values into ASCII codes,
// adding as input to the computer.
$inputs = [
    str_split(rtrim($functions, ',')),
    str_split(rtrim($groups[1], ',')),
    str_split(rtrim($groups[2], ',')),
    str_split(rtrim($groups[3], ',')),
    ['n'], // Don't print realtime
];

foreach ($inputs as $input) {
    for ($x = 0; $x < count($input); $x++) {
        $c->addInput(ord($input[$x]));
    }
    $c->addInput(ord("\n"));
}

// Run the program.
do {
    $exitCode = $c->step();

    if ($c->outputSize() > 0) {
        [$out] = $c->getOutput();

        // Only chr() integers below 300:
        // "amount of space dust it collected as a large, non-ASCII value in a single output instruction."
        if ($out < 300) {
            echo chr($out);
        } else {
            echo "Answer: " . $out . "\n";
        }
    }

    if ($exitCode == 1) break;

} while(true);
