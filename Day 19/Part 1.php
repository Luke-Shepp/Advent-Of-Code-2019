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

$grid = [];

$affectedPoints = 0;

for ($x = 0; $x < 50; $x++) {
    for ($y = 0; $y < 50; $y++) {
        $c = new Computer();
        $c->addInput($x);
        $c->addInput($y);

        $c->run();

        [$point] = $c->getOutput();

        $grid[$y][$x] = $point;

        if ($point === 1) $affectedPoints++;
    }
}

draw($grid);

echo "\nAnswer: $affectedPoints\n";
