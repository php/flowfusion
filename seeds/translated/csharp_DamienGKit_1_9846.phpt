--TEST--
Translated seed (inferredbugs-csharp: DamienGKit/1)
--FILE--
<?php

class Crc32
{
    public static function compute($data)
    {
        return crc32($data);
    }
}

function getTestFileHash($filename)
{
    return file_get_contents($filename);
}

function getBigEndianUInt32($data)
{
    return unpack('N', $data)[1];
}

function main()
{
    $simpleBytesAscii = "Hello, World!";
    $actual1 = Crc32::compute($simpleBytesAscii);

    $simpleBytes2Ascii = "Goodbye, World!";
    $actual2 = Crc32::compute($simpleBytes2Ascii);

    $binary12KFileName = 'path/to/your/12Kbinaryfile.bin'; // Replace with actual path
    $hash12K = getTestFileHash($binary12KFileName);
    $actual3 = getBigEndianUInt32($hash12K);

    $binary1MFileName = 'path/to/your/1Mbinaryfile.bin'; // Replace with actual path
    $hash1M = getTestFileHash($binary1MFileName);
    $actual4 = getBigEndianUInt32($hash1M);
}

main();
--EXPECT--
