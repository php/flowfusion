--TEST--
Translated seed (inferredbugs-csharp: TS3AudioBot/2)
--FILE--
<?php
$tests = [
    ["12.5", "<", "13.1", "ok", "no"],
    ["abc", ">", "ab", "ok", "no"],
    ["1:02", "seek"],
    ["+5", "volume"],
    ["yes no yes", "take"],
    ["last", "history"],
    ["!print a b", "parse"],
];

foreach ($tests as $t) {
    $a = $t[0];
    $op = $t[1];
    if ($op === "<" || $op === ">" || $op === "<=" || $op === ">=" || $op === "==" || $op === "!=") {
        $b = $t[2];
        $da = is_numeric($a) ? (float)$a : null;
        $db = is_numeric($b) ? (float)$b : null;
        $r = $da !== null && $db !== null
            ? ($op === "<" ? $da < $db : ($op === ">" ? $da > $db : ($op === "<=" ? $da <= $db : ($op === ">=" ? $da >= $db : ($op === "==" ? abs($da - $db) < 1e-6 : abs($da - $db) > 1e-6)))))
            : ($op === "<" ? strcmp($a, $b) < 0 : ($op === ">" ? strcmp($a, $b) > 0 : ($op === "<=" ? strcmp($a, $b) <= 0 : ($op === ">=" ? strcmp($a, $b) >= 0 : ($op === "==" ? strcmp($a, $b) === 0 : strcmp($a, $b) !== 0)))));
        $r ? print($t[3] . "\n") : print($t[4] . "\n");
    } elseif ($op === "seek") {
        $span = null;
        if (str_contains($a, ":")) {
            $p = explode(":", $a, 2);
            if (count($p) === 2 && ctype_digit($p[0]) && ctype_digit($p[1])) $span = ((int)$p[0]) * 60 + (int)$p[1];
        } elseif (ctype_digit(ltrim($a, "-"))) {
            $span = (int)$a;
        }
        echo ($span !== null && $span >= 0 ? "ok" : "bad") . "\n";
    } elseif ($op === "volume") {
        $v = (float)substr($a, 1);
        $new = $a[0] === "+" ? 50 + $v : 50 - $v;
        echo (($new >= 0 && $new <= 100) ? "ok" : "bad") . "\n";
    } elseif ($op === "take") {
        $parts = explode(" ", $a);
        echo implode(" ", array_slice($parts, 0, 2)) . "\n";
    } elseif ($op === "history") {
        echo (($a === "last") ? "last" : "next") . "\n";
    } elseif ($op === "parse") {
        echo (ltrim($a)[0] === "!" ? "cmd" : "no") . "\n";
    }
}
--EXPECT--
