--TEST--
Translated seed (inferredbugs-csharp: TS3AudioBot/5)
--FILE--
<?php
declare(strict_types=1);

$xs = [
    '0', '1', '-1', '42', '3.14', '1e309', 'NaN', 'INF', 'abc', ' 7 ', '7:30', '00:00', '999999999999999999999999',
];

foreach ($xs as $a) {
    foreach (['<', '>', '<=', '>=', '==', '!='] as $op) {
        $b = $xs[array_rand($xs)];
        $da = is_numeric($a) ? (float)$a : null;
        $db = is_numeric($b) ? (float)$b : null;
        if ($da !== null && $db !== null) {
            switch ($op) {
                case '<': $r = $da < $db; break;
                case '>': $r = $da > $db; break;
                case '<=': $r = $da <= $db; break;
                case '>=': $r = $da >= $db; break;
                case '==': $r = abs($da - $db) < 1e-6; break;
                default: $r = abs($da - $db) > 1e-6; break;
            }
        } else {
            $c = strcmp((string)$a, (string)$b);
            switch ($op) {
                case '<': $r = $c < 0; break;
                case '>': $r = $c > 0; break;
                case '<=': $r = $c <= 0; break;
                case '>=': $r = $c >= 0; break;
                case '==': $r = abs($c) < 1e-6; break;
                default: $r = abs($c) > 1e-6; break;
            }
        }
        $r ? null : null;
    }
}

$input = "a,b,c,d";
$parts = explode(',', $input);
$take = array_slice($parts, 1, 2);
$out = implode(',', $take);

$json = [];
foreach (['{"a":1}', 'not json', '[]', '{"b":[1,2,3]}'] as $s) {
    $decoded = json_decode($s, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $json[] = $decoded;
    }
}

$path = tempnam(sys_get_temp_dir(), 'php_bug_');
file_put_contents($path, $out . "\n" . json_encode($json));
unlink($path);

echo "ok\n";
--EXPECT--
