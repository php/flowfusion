--TEST--
Translated seed (inferredbugs-csharp: serilog/3)
--FILE--
<?php
$defaultOutputTemplate = "{TimeStamp} [{Level}] {Message:l}{NewLine:l}{Exception:l}";
$path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'serilog-' . date('Y-m-d') . '.log';
$messages = [
    ['time' => date('c'), 'level' => 'Information', 'message' => 'hello', 'exception' => ''],
    ['time' => date('c'), 'level' => 'Error', 'message' => "line1\nline2", 'exception' => 'boom'],
];
$out = '';
foreach ($messages as $e) {
    $out .= strtr($defaultOutputTemplate, [
        '{TimeStamp}' => $e['time'],
        '{Level}' => $e['level'],
        '{Message:l}' => $e['message'],
        '{NewLine:l}' => PHP_EOL,
        '{Exception:l}' => $e['exception'],
    ]) . PHP_EOL;
}
file_put_contents($path, $out);
echo $out;
?>
--EXPECT--
