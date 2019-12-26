<?php

require_once __DIR__ . '/Computer.php';

$c = new Computer();

// (!A || !B || !C) && D && (E || H)
$c->addInputString('NOT A T'); // Temp = If A is hole
$c->addInputString('NOT B J'); // Jump = If B is hole
$c->addInputString('OR T J');  // Jump = jump or temp (A || B holes)
$c->addInputString('NOT C T'); // Temp = If C is hole
$c->addInputString('OR T J');  // Jump = jump or temp (A || B || C holes)
$c->addInputString('AND D J'); // Jump = jump and D is NOT hole.
$c->addInputString('AND E T'); // Temp = If E is land
$c->addInputString('OR H T');  // Temp = temp or H is land (E || H land)
$c->addInputString('AND T J'); // Jump = temp and jump.
$c->addInputString('RUN');

$c->run();

foreach ($c->getOutput() as $chr) {
    if ($chr > 200) {
        echo "Answer: " . $chr . "\n";
    } else {
        echo chr($chr);
    }
}
