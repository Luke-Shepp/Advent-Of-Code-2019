<?php
// Luke Sheppard
// Advent of Code 2019 - Day 1 - Part 1 - https://adventofcode.com/2019/day/1

$input = file_get_contents(__DIR__ . '/input.csv');

$modules = explode(',', $input);

$fuel = 0;

foreach ($modules as $mass) {
  $fuel += floor($mass / 3) - 2;
}

echo $fuel . "\n";
