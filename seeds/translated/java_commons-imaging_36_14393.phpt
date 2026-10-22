--TEST--
Translated seed (inferredbugs-java: commons-imaging/36)
--FILE--
<?php

$testByteArrays = [
    [],
    range(0, 0),
    range(0, 255),
    array_fill(0, 256, 0),
    array_reduce(range(0, 65535), function($carry, $item) {
        $carry[] = $item & 0xff;
        $carry[] = ($item >> 8) & 0xff;
        return $carry;
    }, [])
];

$tempFile = tempnam(sys_get_temp_dir(), 'raw_') . '.bin';

foreach ($testByteArrays as $src) {
    file_put_contents($tempFile, implode('', array_map('chr', $src)));
    if (count($src) !== filesize($tempFile)) {
        echo "Error: Bytes written do not match file size.\n";
        exit(1);
    }
}

unlink($tempFile);
--EXPECT--
