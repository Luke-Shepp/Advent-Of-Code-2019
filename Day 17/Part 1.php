<?php

require_once __DIR__ . '/Computer.php';

function draw($data) {
    foreach ($data as $d) {
        foreach ($d as $val) {
            echo $val;
        }
        echo "\n";
    }
}

$c = new Computer();

$c->run();

$grid = [];
$row  = [];

foreach ($c->getOutput() as $val) {
    if ($val == 10) {
        $grid[] = $row;
        $row = [];
    } else {
        $row[] = chr($val);
    }
}

// draw($grid);

$alignmentParamSum = 0;

foreach ($grid as $i => $js) {
    foreach ($js as $j => $val) {
        // Find an intersection.
        // An intersection can be determined if for any point, the points
        // below, above, left and right of the point are also scaffold.
        if ($grid[$i][$j] != '.' && ($grid[$i][$j+1] ?? '') != '.' && ($grid[$i][$j-1] ?? '') != '.') {
            if (($grid[$i+1][$j] ?? '') != '.' && ($grid[$i-1][$j] ?? '') != '.') {
                $alignmentParamSum += $i * $j;

                // Mark the intersection
                $grid[$i][$j] = 'O';
            }
        }
    }
}

// draw($grid);

echo 'Answer: ' . $alignmentParamSum . "\n";
