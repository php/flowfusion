--TEST--
Translated seed (inferredbugs-csharp: USharp/5)
--FILE--
<?php
$classes = [
    ['name' => 'Base', 'super' => null, 'vtable' => ['a', 'b', 'c']],
    ['name' => 'Mid', 'super' => 0, 'vtable' => ['a', 'b', 'c']],
    ['name' => 'Leaf', 'super' => 1, 'vtable' => ['a', 'x', 'c']],
];

$index = 0;
while (true) {
    if ($classes[0]['vtable'][$index] === $classes[1]['vtable'][$index] && $classes[0]['vtable'][$index] !== $classes[2]['vtable'][$index]) {
        $redirect = $classes[0]['vtable'][$index];
        break;
    }
    $index++;
}

foreach ([0, 1, 2] as $i) {
    if ($classes[$i]['vtable'][$index] === $redirect) {
        $owner = $i;
        while (($super = $classes[$owner]['super']) !== null) {
            if ($classes[$super]['vtable'][$index] === $classes[$i]['vtable'][$index]) {
                $owner = $super;
            }
            $owner = $super;
        }
        $classes[$i]['orig'] = $classes[$i]['vtable'][$index];
        $classes[$i]['vtable'][$index] = $redirect;
    }
}

echo $index . "\n";
echo $redirect . "\n";
echo implode(',', $classes[0]['vtable']) . "\n";
echo implode(',', $classes[1]['vtable']) . "\n";
echo implode(',', $classes[2]['vtable']) . "\n";
--EXPECT--
