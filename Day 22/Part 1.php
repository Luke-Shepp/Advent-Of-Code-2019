<?php

$input    = file_get_contents(__DIR__ . '/input.txt');
$shuffles = explode("\n", $input);

$deck = range(0, 10006);

foreach ($shuffles as $shuffle) {
    $matches = [];

    if (preg_match('/^deal with increment ([0-9]+)$/', $shuffle, $matches)) {
        dealWithIncrement($deck, $matches[1]);
    } elseif ($shuffle == 'deal into new stack') {
        dealIntoNewStack($deck);
    } elseif (preg_match('/^cut (\-?[0-9]+)$/', $shuffle, $matches)) {
        cutNCards($deck, (int) $matches[1]);
    }
}

echo "Card 2019 is at position: " . array_search(2019, $deck) . "\n";

function dealIntoNewStack(&$deck) {
    $deck = array_reverse($deck, false);
}

function cutNCards(&$deck, $n) {
    if ($n < 0) {
        array_unshift($deck, ...array_splice($deck, $n, abs($n)));
    } else {
        array_push($deck, ...array_splice($deck, 0, $n));
    }
}

function dealWithIncrement(&$deck, $inc) {
    $table = [];

    $n = 0;

    foreach ($deck as $card) {
        $table[$n] = $card;

        $n += $inc;

        if ($n > count($deck)) {
            $n %= count($deck);
        }
    }

    ksort($table);

    $deck = $table;
}
