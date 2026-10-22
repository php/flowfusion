--TEST--
Translated seed (inferredbugs-csharp: csly/2)
--FILE--
<?php
$cases = [
    ["{ 'one': 1, 'bug':{,} }", 'comma'],
    ["2 + 3 + + 2", 'plus'],
    ["2 @ 2", 'at'],
];

foreach ($cases as [$s, $kind]) {
    $len = strlen($s);
    $line = 1;
    $col = 1;
    $in = false;
    $quote = '';
    for ($i = 0; $i < $len; $i++) {
        $ch = $s[$i];
        if ($ch === "\n") {
            $line++;
            $col = 1;
            continue;
        }
        if ($in) {
            if ($ch === $quote) $in = false;
            $col++;
            continue;
        }
        if ($ch === "'" || $ch === '"') {
            $in = true;
            $quote = $ch;
            $col++;
            continue;
        }
        if ($kind === 'at' && $ch === '@') {
            break;
        }
        if ($kind === 'plus' && $ch === '+' && $i > 0 && $s[$i - 1] === ' ') {
            if ($i + 1 < $len && $s[$i + 1] === ' ') {
                break;
            }
        }
        if ($kind === 'comma' && $ch === ',' && $i > 0 && $s[$i - 1] === '{') {
            break;
        }
        $col++;
    }
}
?>
--EXPECT--
