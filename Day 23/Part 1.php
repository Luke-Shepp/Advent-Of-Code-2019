<?php

require_once __DIR__ . '/Computer.php';

$computers   = [];
$packetQueue = [];

for ($i = 0; $i < 50; $i++) {
    $c = new Computer;
    $c->addInput($i);

    $computers[] = $c;
}

do {
    foreach ($computers as $i => $c) {
        $exitCode = 0;

        try {
            $exitCode = $c->step();
        } catch (ExpectsInputException $e) {
            if (empty($packetQueue[$i])) {
                $c->addInput(-1);
            } else {
                $packet = array_shift($packetQueue[$i]);
                $c->addInput($packet[0]);
                $c->addInput($packet[1]);
            }
        }

        if ($c->outputSize() == 3) {
            [$dest, $x, $y] = $c->getOutput();

            $packetQueue[$dest][] = [$x, $y];

            echo "$i => $dest : $x, $y\n";

            if ($dest == 255) {
                echo "Answer: $y\n";
                break 2;
            }
        }
    }
} while (true);
