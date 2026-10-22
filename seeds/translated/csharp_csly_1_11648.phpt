--TEST--
Translated seed (inferredbugs-csharp: csly/1)
--FILE--
<?php
$cases = [
    "{\n  'one': 1,\n  'bug':{,}\n}",
    " 2 + 3 + + 2",
    "2 @ 2",
];

foreach ($cases as $s) {
    json_decode($s);
    preg_match('/^\s*2\s+\+\s+3\s+\+\s+\+\s+2$/', $s);
    preg_match('/^\s*2\s+@\s+2$/', $s);
}
--EXPECT--
