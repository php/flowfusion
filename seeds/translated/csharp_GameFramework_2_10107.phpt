--TEST--
Translated seed (inferredbugs-csharp: GameFramework/2)
--FILE--
<?php
$fs = [];
$p1 = str_replace('\\', '/', 'a\\b');
$p2 = str_replace('\\', '/', 'c\\d');
$fs[$p1] = ['fullPath' => $p1, 'alive' => true];
$fs[$p2] = ['fullPath' => $p2, 'alive' => true];
while (count($fs) > 0) {
    foreach ($fs as $k => $v) {
        $fs[$k]['alive'] = false;
        unset($fs[$k]);
        break;
    }
}
$fs = [];
$paths = ['x/y', 'x//y', './z/../z'];
foreach ($paths as $p) {
    $rp = preg_replace('#/+#', '/', str_replace('\\', '/', $p));
    if ($rp === '' || isset($fs[$rp])) {
        continue;
    }
    $fs[$rp] = ['fullPath' => $rp, 'alive' => true];
}
$all = array_values($fs);
$results = [];
$results = [];
foreach ($fs as $v) {
    $results[] = $v;
}
echo count($all) . "\n";
echo count($results) . "\n";
?>
--EXPECT--
