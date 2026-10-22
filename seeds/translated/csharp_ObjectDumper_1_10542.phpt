--TEST--
Translated seed (inferredbugs-csharp: ObjectDumper/1)
--FILE--
<?php
$indentChar = "\t";
$indentSize = 3;
$lineBreakChar = "\n";
$level = 2;
$sb = '';

$space = str_repeat($indentChar, $level * $indentSize);
$sb .= $space . "alpha";
$sb .= $lineBreakChar;
$sb .= sprintf("value:%d/%s", 7, "x");

$found = [];
$values = [null, 0, "0", 1, 1.0, false, true, [], [1], (object)["a" => 1], (object)["a" => 1]];
foreach ($values as $v) {
    if ($v === null) {
        continue;
    }
    $h = is_object($v) ? spl_object_id($v) : crc32(serialize($v));
    for ($i = 0, $n = count($found); $i < $n; $i++) {
        if ($found[$i] === $h) {
            $sb .= "|dup";
            continue 2;
        }
    }
    $found[] = $h;
}

echo $sb;
?>
--EXPECT--
