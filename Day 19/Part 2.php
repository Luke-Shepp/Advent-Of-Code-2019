<?php

require_once __DIR__ . '/Computer.php';

$grid    = [];
$startX  = 0;
$endX    = 0;
$boxSize = 100;

// For some reason, there's a break in the beam in the first few rows, so skipping them..
$y = 4;

$boxSize--;

do {
    // Find the start of the beam.
    while (get_point($startX, $y) == 0) $startX++;

    // Find the end of the beam on this row
    if ($endX == 0) $endX = $startX;
    while (get_point($endX, $y) == 1) $endX++;

    // With the box being 100*100, it also needs 100 diagonally, therefore walking
    // along the end of the beam boundary with the potential top right of the box,
    // check to see if there is enough diagonal space (down + left) to fit the box.
    // If so, find the top left of the box from the current top right position
    // and use that in the answer calculation!
    if (get_point(($endX - 1) - $boxSize, $y + $boxSize) == 1) {
        echo "Answer: " . (((($endX - 1) - $boxSize) * 10000) + $y) . "\n";
        break;
    }

    $y++;

} while(true);

function get_point($x, $y) {
    $c = new Computer();
    $c->addInput($x);
    $c->addInput($y);
    $c->run();

    return $c->getOutput()[0];
}
