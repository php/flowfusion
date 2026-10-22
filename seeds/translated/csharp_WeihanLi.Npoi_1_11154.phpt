--TEST--
Translated seed (inferredbugs-csharp: WeihanLi.Npoi/1)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_csv_bug_' . getmypid();
@mkdir($dir, 0777, true);

$rows = [];
$rows[] = [null];
for ($i = 0; $i < 100000; $i++) {
    $rows[] = [$i + 1, "Title_$i", implode(',', range(1, 200)) . "__$i"];
}
$rows[] = [999, implode(',', array_fill(0, 10, '1')), null];

$f = fopen($dir . DIRECTORY_SEPARATOR . 'test.csv', 'w');
foreach ($rows as $r) {
    fputcsv($f, $r);
}
fclose($f);

$f = fopen($dir . DIRECTORY_SEPARATOR . 'test.csv', 'r');
$parsed = [];
while (($r = fgetcsv($f)) !== false) {
    $parsed[] = $r;
}
fclose($f);

$parsed[1][1] = ',tadadada';
$parsed[0][2] = 'value2,345';

$f = fopen($dir . DIRECTORY_SEPARATOR . 'test.1.csv', 'w');
foreach ($parsed as $r) {
    fputcsv($f, $r);
}
fclose($f);

$f = fopen($dir . DIRECTORY_SEPARATOR . 'test.1.csv', 'r');
$parsed2 = [];
while (($r = fgetcsv($f)) !== false) {
    $parsed2[] = $r;
}
fclose($f);

$nums = [1, 2, 3, 5, 4];
$f = fopen($dir . DIRECTORY_SEPARATOR . 'nums.csv', 'w');
fputcsv($f, $nums);
fclose($f);

$f = fopen($dir . DIRECTORY_SEPARATOR . 'nums.csv', 'r');
$line = fgetcsv($f);
fclose($f);

echo implode(',', $line) . PHP_EOL;
echo count($parsed2) . PHP_EOL;
?>
--EXPECT--
