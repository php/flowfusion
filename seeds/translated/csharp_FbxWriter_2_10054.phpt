--TEST--
Translated seed (inferredbugs-csharp: FbxWriter/2)
--FILE--
<?php

function main($args) {
    echo "Start\n";
    $inputFile = $args[1];
    $outputFile = $args[2];

    $handle = fopen($inputFile, "rb");
    $version = fread($handle, 4);
    fclose($handle);

    $node = unpack("C*", $version);

    $handle = fopen($outputFile, "wb");
    fwrite($handle, pack("C*", ...$node));
    fclose($handle);
}

main($argv);
--EXPECT--
