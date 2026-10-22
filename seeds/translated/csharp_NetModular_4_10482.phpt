--TEST--
Translated seed (inferredbugs-csharp: NetModular/4)
--FILE--
<?php
$paths = [null, '', __FILE__, __DIR__ . DIRECTORY_SEPARATOR . 'no_such_file_' . uniqid() . '.pdf'];

foreach ($paths as $p) {
    try {
        if ($p === null) {
            throw new RuntimeException('file path is null');
        }
        if (!is_file($p)) {
            throw new RuntimeException('file not found: ' . $p);
        }
        $h = fopen($p, 'rb');
        if ($h !== false) {
            fread($h, 16);
            fclose($h);
        }
    } catch (Throwable $e) {
    }
}

$stream = fopen('php://temp', 'w+b');
if ($stream !== false) {
    fwrite($stream, "%PDF-1.4\n");
    rewind($stream);
    $data = stream_get_contents($stream);
    if ($data !== false) {
        strlen($data);
    }
    fclose($stream);
}
?>
--EXPECT--
