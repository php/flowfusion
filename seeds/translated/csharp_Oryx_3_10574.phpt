--TEST--
Translated seed (inferredbugs-csharp: Oryx/3)
--FILE--
<?php
$source = sys_get_temp_dir();
$out = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('', true);
$mid = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('', true);

$cases = [
    [null, $out, null],
    [sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('', true), $out, null],
    [$source, null, null],
    [$source, $out, null],
    [$source, $out, $mid],
];

foreach ($cases as [$src, $output, $intermediate]) {
    $help = false;
    $exit = 0;
    $messages = [];

    if ($src === null || $output === null) {
        $help = true;
        $exit = 1;
    } elseif (!is_dir($src)) {
        $messages[] = 'Could not find the source code folder';
        $exit = 1;
    } elseif ($intermediate !== null) {
        $exit = 0;
    }

    if ($help) {
        $messages[] = 'Usage:';
    }

    if ($src === $source && $output !== null && $intermediate === null && is_dir($src)) {
        if ($exit !== 0) {
            $exit = 1;
        }
    }

    if ($src === $source && $output !== null && $intermediate !== null) {
        $exit = 0;
        $messages = [];
    }

    if ($exit === 0 && $messages) {
        throw new RuntimeException('unexpected output');
    }
}
?>
--EXPECT--
