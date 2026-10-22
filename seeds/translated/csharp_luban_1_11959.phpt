--TEST--
Translated seed (inferredbugs-csharp: luban/1)
--FILE--
<?php
$minConsoleLogLevel = 2;

$levels = ['Trace', 'Debug', 'Info', 'Warn', 'Error', 'Fatal'];
$log = [];
for ($i = $minConsoleLogLevel; $i < count($levels); $i++) {
    $msg = date('Y-m-d H:i:s') . '|' . $levels[$i] . '|message';
    if ($levels[$i] === 'Fatal') {
        try {
            throw new Exception("boom");
        } catch (Throwable $e) {
            $msg .= PHP_EOL . $e . PHP_EOL . $e->getTraceAsString();
        }
    }
    $log[] = $msg;
}

echo implode(PHP_EOL, $log), PHP_EOL;
--EXPECT--
