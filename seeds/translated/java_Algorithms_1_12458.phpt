--TEST--
Translated seed (inferredbugs-java: Algorithms/1)
--FILE--
<?php

function findNthMostRepeatedElement(array $numbers, int $position): int {
    validateInput($numbers, $position);
    $result = null;
    $counter = [];

    foreach ($numbers as $i) {
        if (!isset($counter[$i])) {
            $counter[$i] = 1;
        } else {
            $counter[$i]++;
        }
    }

    foreach ($counter as $candidate => $count) {
        if ($count === $position) {
            $result = $candidate;
            break;
        }
    }

    validateResult($result);
    return $result;
}

function validateInput(array $numbers, int $position): void {
    if (count($numbers) === 0 || $position <= 0) {
        throw new InvalidArgumentException("You can't pass empty arrays or position values less than 1 as parameter.");
    }
}

function validateResult(?int $result): void {
    if ($result === null) {
        throw new InvalidArgumentException("There are no elements repeated n times in the array passed as argument");
    }
}

$numbers = [1, 2, 2, 3, 3, 3, 4];
$position = 2;
$result = findNthMostRepeatedElement($numbers, $position);
echo $result;
--EXPECT--
