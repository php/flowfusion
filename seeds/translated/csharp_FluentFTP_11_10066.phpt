--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/11)
--FILE--
<?php
$stream = fopen('php://temp', 'w+');
$writer = null;
$flushOnWrite = false;

if ($stream !== false) {
    $writer = $stream;
}

if ($writer !== null) {
    fwrite($writer, "hello");
    if ($flushOnWrite) {
        fflush($writer);
    }
    fwrite($writer, PHP_EOL);
    if ($flushOnWrite) {
        fflush($writer);
    }
}

$writer = null;
$stream = null;
?>
--EXPECT--
