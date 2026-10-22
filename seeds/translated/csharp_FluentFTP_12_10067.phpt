--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/12)
--FILE--
<?php
$stream = fopen('php://temp', 'w+');
$writer = null;
$flushOnWrite = false;

if ($writer === null && $stream !== null) {
    $writer = $stream;
}

if ($writer !== null) {
    flock($writer, LOCK_EX);
    fwrite($writer, "message");
    if ($flushOnWrite) {
        fflush($writer);
    }
    flock($writer, LOCK_UN);
}

$flushOnWrite = true;

if ($writer !== null) {
    flock($writer, LOCK_EX);
    fwrite($writer, "line\n");
    if ($flushOnWrite) {
        fflush($writer);
    }
    flock($writer, LOCK_UN);
}

if ($writer !== null) {
    fclose($writer);
    $writer = null;
}

$stream = null;
?>
--EXPECT--
