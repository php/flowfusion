--TEST--
Translated seed (inferredbugs-csharp: Sonarr/4)
--FILE--
<?php
$args = $argv;
array_shift($args);

if ($args === null || count($args) !== 2) {
    $args = ['123', 'x'];
}

$id = filter_var($args[0], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) {
    $id = 1;
}

$pid = getmypid();
$procPath = @readlink("/proc/$pid/exe");
if ($procPath === false || $procPath === '') {
    $procPath = __FILE__;
}

$targetFolder = dirname($procPath);
echo "Starting update process. Target Path:$targetFolder\n";
echo "Arguments verified. [$args[0]] , [$args[1]]\n";
echo "NzbDrone processId:$id\n";
echo "Updating NzbDrone to version 1.0\n";
echo "Copying log files to application directory.\n";
echo "Starting NzbDrone Update Client\n";
--EXPECT--
