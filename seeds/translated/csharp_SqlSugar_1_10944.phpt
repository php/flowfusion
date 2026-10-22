--TEST--
Translated seed (inferredbugs-csharp: SqlSugar/1)
--FILE--
<?php
$body = $argv[1] ?? 'member';
$single = ($argv[2] ?? '1') === '1';

if ($body === 'invalid') {
    try {
        throw new Exception('ExpFileldError');
    } catch (Throwable $e) {
    }
} else {
    if ($single) {
        $reval = 'Name';
    } else {
        $reval = 'x => x.Name';
    }
    echo $reval, PHP_EOL;
}
?>
--EXPECT--
