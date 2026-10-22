--TEST--
Translated seed (inferredbugs-csharp: serilog/2)
--FILE--
<?php
$levels = ['Minimum', 'Debug', 'Information', 'Warning', 'Error', 'Fatal'];
$template = "{TimeStamp} [{Level}] {Message:l}{NewLine:l}{Exception:l}";
$pathFormat = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'myapp-{0}.log';
$path = sprintf($pathFormat, date('Y-m-d'));
$lines = [];
foreach ([$template, str_replace(['{TimeStamp}', '{Level}', '{Message:l}', '{NewLine:l}', '{Exception:l}'], ['2026-05-13T00:00:00Z', 'Information', 'hello', PHP_EOL, ''], $template)] as $t) {
    $lines[] = $t;
}
file_put_contents($path, implode(PHP_EOL, $lines) . PHP_EOL, FILE_APPEND);
echo implode('|', $levels) . PHP_EOL;
?>
--EXPECT--
