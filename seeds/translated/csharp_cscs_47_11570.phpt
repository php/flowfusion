--TEST--
Translated seed (inferredbugs-csharp: cscs/47)
--FILE--
<?php
function v($x = null) { return $x; }

$a = [];
$a['x'] = ['y' => 1];
$a['x']['y'] += 2;
$a['x']['z'] = [1, 2, 3];
unset($a['x']['z'][1]);
$a['x']['z'] = array_values($a['x']['z']);
$a['m'] = [];
$a['m']['k'] = 'v';
$b = $a;
$b['x']['y'] = $b['x']['y'] . '';
$c = $b['x']['z'][0] ?? null;
$d = isset($a['x']['z'][1]) ? $a['x']['z'][1] : null;

$s = "12.5";
$n = (int)$s;
$f = (float)$s;
$bool = filter_var("true", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
$dec = (string)($f + $n);

$e = [];
$e['A'] = 0;
$e['B'] = 1;
$e['C'] = 2;

class C {
    public $p = 1;
    public $q = [];
}
$o = new C();
$o->q['r'] = 4;
$o->q['r']++;

$x = [1, [2, 3], ['k' => 4]];
$y = $x;
$y[1][0] = 9;
$y[2]['k'] = 8;

for ($i = 0; $i < 3; $i++) {
    if ($i === 1) continue;
    if ($i === 2) break;
    $a['loop'] = $i;
}

try {
    if ($a['x']['y'] > 0) {
        throw new Exception((string)$a['x']['y']);
    }
} catch (Exception $ex) {
    $msg = $ex->getMessage();
}

echo "";
?>
--EXPECT--
