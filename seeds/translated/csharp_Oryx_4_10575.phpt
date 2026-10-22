--TEST--
Translated seed (inferredbugs-csharp: Oryx/4)
--FILE--
<?php
$source = sys_get_temp_dir();
$output = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('', true);
$intermediate = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('', true);

$cases = [
    [null, $output, null],
    [$source, $output, null],
    [null, null, null],
    [$source, $output, $intermediate],
];

foreach ($cases as [$src, $out, $int]) {
    $help = false;
    $msg = '';
    $exit = 0;

    if ($src === null || $out === null) {
        $help = true;
        $exit = 1;
    } elseif (!is_dir($src)) {
        $msg = 'Could not find the source code folder';
        $exit = 1;
    } elseif ($int !== null) {
        $exit = 0;
    }

    if ($help) {
        $msg = "Usage:";
    }

    if ($exit === 0 && $msg !== '') {
        throw new RuntimeException('unexpected output');
    }
    if ($exit !== 0 && $msg === '') {
        throw new RuntimeException('missing output');
    }
}
?>
--EXPECT--
