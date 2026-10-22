--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/4)
--FILE--
<?php
declare(strict_types=1);

$stream = fopen('php://temp', 'w+b');
fwrite($stream, "PK\x03\x04");
rewind($stream);

$sheetName = '';
$excelType = null;

try {
    if ($sheetName === '') {
        throw new RuntimeException('Sheet name can not be empty or null');
    }
    if ($excelType === null) {
        throw new RuntimeException('Please specify excelType');
    }
} catch (Throwable $e) {
}

$path = tempnam(sys_get_temp_dir(), 'miniexcel_');
file_put_contents($path, "PK\x03\x04");

$data = [
    ['A' => 1, 'B' => null, 'C' => ''],
    ['A' => 0, 'B' => '0', 'C' => false],
];

$out = json_encode($data, JSON_THROW_ON_ERROR);
file_put_contents($path, $out);

$contents = file_get_contents($path);
if ($contents === false) {
    unlink($path);
    exit(0);
}

$decoded = json_decode($contents, true);
if (is_array($decoded)) {
    foreach ($decoded as $row) {
        foreach ($row as $k => $v) {
            $x = $v;
        }
    }
}

fclose($stream);
unlink($path);
?>
--EXPECT--
