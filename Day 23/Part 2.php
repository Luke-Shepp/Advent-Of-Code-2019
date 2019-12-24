<?php

require_once __DIR__ . '/Computer.php';

$computers   = [];
$packetQueue = [];
$nat = null;
$lastNatDelivered = null;

for ($i = 0; $i < 50; $i++) {
    $c = new Computer;
    $c->addInput($i);

    $computers[] = $c;
}

do {
    $allIdle = true;

    foreach ($computers as $i => $c) {
        $exitCode = 0;

        try {
            // Step until input requested.
            do {
                $c->step();

                if ($c->outputSize() == 3) {
                    [$dest, $x, $y] = $c->getOutput();

                    if ($dest == 255) {
                        $nat = [$x, $y];
                    } else {
                        $packetQueue[$dest][] = [$x, $y];
                    }

                    // echo "$i => $dest : $x, $y\n";
                }
            } while(true);
        } catch (ExpectsInputException $e) {
            if (empty($packetQueue[$i])) {
                $c->addInput(-1);
            } else {
                $allIdle = false;
                $packet = array_shift($packetQueue[$i]);
                $c->addInput($packet[0]);
                $c->addInput($packet[1]);
            }
        }
    }

    // Everything is idle, kick it back into gear.
    if (packetsInQueue() == 0 && $allIdle && !is_null($nat)) {
        if (!is_null($lastNatDelivered) && $nat[1] == $lastNatDelivered[1]) {
            echo "Answer: " . $lastNatDelivered[1] . "\n";
            break;
        }

        // Push the contents of the NAT back onto the first computers queue.
        $packetQueue[0][] = $nat;
        $lastNatDelivered = $nat;
    }

} while (true);


function packetsInQueue() {
    global $packetQueue;
    $cnt = 0;
    foreach ($packetQueue as $dest => $packets) {
        $cnt += count($packets);
    }
    return $cnt;
}
