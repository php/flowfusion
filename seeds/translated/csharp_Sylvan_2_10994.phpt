--TEST--
Translated seed (inferredbugs-csharp: Sylvan/2)
--FILE--
<?php
$counts = [100, 500, 1000, 5000];
foreach ($counts as $count) {
    for ($i = 0; $i < 100; $i++) {
        $stream = fopen('php://temp', 'w+b');
        for ($j = 0; $j < $count; $j++) {
            fwrite($stream, "This is a string");
            fwrite($stream, "12345");
        }
        rewind($stream);
        while (!feof($stream)) {
            fread($stream, 8192);
        }
        fclose($stream);
    }
}
?>
--EXPECT--
