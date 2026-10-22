--TEST--
Translated seed (inferredbugs-csharp: Stardust/3)
--FILE--
<?php
$stateFile = __DIR__ . '/staragent_state.json';
$state = ['server' => '', 'code' => '', 'secret' => '', 'channel' => 'stable', 'services' => []];
if (is_file($stateFile)) {
    $tmp = json_decode(file_get_contents($stateFile), true);
    if (is_array($tmp)) $state = array_merge($state, $tmp);
}
if ($state['server'] === '') $state['server'] = 'http://localhost:6600';

echo "服务端：{$state['server']}\n";
echo "请输入新的服务端：\n";

$addr = trim((string)fgets(STDIN));
if ($addr === '') $addr = 'http://star.newlifex.com:6600';
$state['server'] = $addr;
file_put_contents($stateFile, json_encode($state, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
echo "服务端修改为：{$addr}\n";

echo 'FullPath:' . realpath('.') . "\n";
echo 'BasePath:' . getcwd() . "\n";
echo 'TempPath:' . sys_get_temp_dir() . "\n";

$info = [
    'Hostname' => php_uname('n'),
    'OS' => PHP_OS,
    'PHPVersion' => PHP_VERSION,
    'SAPI' => PHP_SAPI,
    'TempDir' => sys_get_temp_dir(),
];
foreach ($info as $k => $v) {
    echo $k . ":\t" . $v . "\n";
}
?>
--EXPECT--
