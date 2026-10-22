--TEST--
Translated seed (inferredbugs-csharp: Sonarr/3)
--FILE--
<?php
$argc < 2 && exit(0);
if ($argc !== 3) exit(0);

$id = filter_var($argv[1], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
if ($id === false) exit(0);

$pid = getmypid();
$path = "/proc/$pid/exe";
$exe = @readlink($path);
if ($exe === false) $exe = __FILE__;
$appPath = dirname($exe);

echo "Starting update process\n";
echo $appPath . "\n";
echo $argv[2] . "\n";
?>
--EXPECT--
