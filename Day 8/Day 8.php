<?php
// Luke Sheppard
// Advent of Code 2019 - Day 8 - Part 1 & 2 - https://adventofcode.com/2019/day/8

$input = file_get_contents(__DIR__ . '/input.txt');

$imageSize = [
    'x' => 25,
    'y' => 6
];

function count_values($array, $value) {
    $count = 0;

    foreach ($array as $subArray) {
        $count += array_count_values($subArray)[$value] ?? 0;
    }

    return $count;
}

$layer  = 0;
$row    = 0;
$column = 0;

$layers = [];

for ($i = 0; $i < strlen($input); $i++) {
    if ($column == $imageSize['x']) {
        $column = 0;
        $row++;
    }

    if ($row >= $imageSize['y']) {
        $layer++;
        $column = 0;
        $row = 0;
    }

    $layers[$layer][$row][$column] = $input[$i];

    $column++;
}



////// Part 1
$fewestZeroLayer = $layers[0];

foreach ($layers as $i => $layer) {
    if (count_values($layer, 0) < count_values($fewestZeroLayer, 0)) {
        $fewestZeroLayer = $layer;
    }
}

echo "Part 1: " . (count_values($fewestZeroLayer, 1) * count_values($fewestZeroLayer, 2)) . "\n";



////// Part 2
echo "\n\nPart 2: \n";

$outputImage = [];

// Build the resulting image respecting transparency between layers
for ($row = 0; $row < $imageSize['y']; $row++) {
    for ($col = 0; $col < $imageSize['x'] ; $col++) {
        foreach ($layers as $layer => $rows) {
            $pixel = $rows[$row][$col];
            if ($pixel != 2) {
                $outputImage[$row][$col] = $pixel;
                break;
            }
        }
    }
}

// Output the final image
foreach ($outputImage as $cols) {
    foreach ($cols as $col) {
        if ($col == 1) {
            echo "█";
        } else {
            echo " ";
        }
    }
    echo "\n";
}
