--TEST--
Translated seed (inferredbugs-csharp: aenetmail/1)
--FILE--
<?php
$cases = [
    ["* 1 EXISTS", "* 1 EXPUNGE", "* 2 EXISTS", "A1 OK IDLE completed"],
    ["* 3 EXISTS", "* 3 EXPUNGE", "* 4 EXISTS", "A2 OK IDLE completed"],
    ["* 0 EXISTS", "* 1 EXISTS", "* 1 EXPUNGE", "A3 OK IDLE completed"],
];

foreach ($cases as $responses) {
    $last = null;
    foreach ($responses as $resp) {
        if (strpos($resp, 'OK IDLE') !== false) {
            break;
        }
        $parts = explode(' ', $resp);
        if (count($parts) >= 3 && $parts[0] === '*') {
            $count = (int)$parts[1];
            $kind = $parts[2];
            if ($kind === 'EXISTS' && $last !== 'EXPUNGE' && $count > 0) {
                $x = $count;
                $y = strrev((string)$x);
            } elseif ($kind === 'EXPUNGE') {
                $z = $count + 1;
                $w = $z ^ $count;
            }
            $last = $kind;
        }
    }
}
?>
--EXPECT--
