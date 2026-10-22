--TEST--
Translated seed (inferredbugs-csharp: JUST.net/1)
--FILE--
<?php
$json = '{"items":[{"g":"a","v":1,"n":2},{"g":"a","v":3,"n":4},{"g":"b","v":5,"n":6}],"x":{"y":[{"z":10},{"z":20}]}}';
$data = json_decode($json, true);

$groups = [];
foreach ($data['items'] as $item) {
    $k = $item['g'];
    if (!isset($groups[$k])) $groups[$k] = ['g' => $k, 'sum' => 0, 'concat' => ''];
    $groups[$k]['sum'] += (int)$item['v'];
    $groups[$k]['concat'] .= (string)$item['n'];
}

$result = array_values($groups);
echo json_encode($result, JSON_UNESCAPED_SLASHES), "\n";

$nums = [2147483647, 1, -2, 3];
$sum = 0;
$max = null;
$min = null;
foreach ($nums as $i => $n) {
    $sum += $n;
    if ($i === 0 || $n > $max) $max = $n;
    if ($i === 0 || $n < $min) $min = $n;
}
echo $sum, "\n";
echo $max, "\n";
echo $min, "\n";

$path = $data['x']['y'];
$last = $path[count($path) - 1]['z'];
echo $last, "\n";

$vals = ['10', '20', '30'];
echo implode('', $vals), "\n";
echo (string)(strpos('abcdef', 'cd') !== false), "\n";
echo (string)(count($path) > 0), "\n";
?>
--EXPECT--
