--TEST--
Translated seed (inferredbugs-csharp: Statiq.Framework/1)
--FILE--
<?php
$cfg = [
    "A" => [
        "0" => "x",
        "1" => ["2" => "y"],
        "3" => "z",
    ],
    "b" => [
        "00" => "p",
        "1" => "q",
    ],
    "C" => [
        "0" => ["0" => "n"],
        "1" => ["0" => "m"],
    ],
];

function build($node, $path = null) {
    $dict = [];
    $list = $path === null ? null : [];
    $index = 0;
    foreach ($node as $k => $v) {
        if ($list !== null && (!ctype_digit((string)$k) || (int)$k !== $index++)) {
            $list = null;
        }
        if (is_array($v)) {
            $value = build($v, $path === null ? $k : $path . ":" . $k);
            if ($list !== null) $list[] = $value;
            $dict[$k] = $value;
        } else {
            if ($list !== null) $list[] = $v;
            $dict[$k] = $v;
        }
    }
    return $list !== null ? $list : $dict;
}

$data = build($cfg);

$flat = [];
$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($data));
foreach ($it as $k => $v) {
    $flat[] = $k . "=" . (is_array($v) ? json_encode($v) : $v);
}

echo implode(";", $flat), PHP_EOL;
--EXPECT--
