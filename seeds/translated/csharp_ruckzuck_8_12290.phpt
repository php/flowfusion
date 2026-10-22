--TEST--
Translated seed (inferredbugs-csharp: ruckzuck/8)
--FILE--
<?php
declare(strict_types=1);

$dir = __DIR__ . '/wwwroot';
@mkdir($dir . '/rzsw', 0777, true);
@mkdir($dir . '/files', 0777, true);
@mkdir($dir . '/icons', 0777, true);

$contentType = 'application/json';
$catalogTTL = 1;
$localURL = 'http://localhost:5000';

$normalize = static function (string $s): string {
    return preg_replace('/[^[:alnum:]]+/u', '', $s) ?? '';
};

$sw = [
    'ProductName' => 'A+B',
    'ProductVersion' => '1.0',
    'Manufacturer' => 'Acme/Corp',
    'ContentID' => 'CID123',
    'Files' => [
        ['URL' => 'http://example.invalid/file.bin', 'FileName' => 'file.bin', 'FileHash' => '', 'HashType' => '']
    ]
];

$key = $normalize($sw['ProductName'] . $sw['ProductVersion'] . $sw['Manufacturer']);
$swFile = $dir . '/rzsw/' . $key . '.json';

if (file_exists($swFile)) {
    $age = time() - filemtime($swFile);
    if ($catalogTTL === 0 || $age <= ($catalogTTL * 3600 + 60)) {
        $data = file_get_contents($swFile);
        if ($data !== false) {
            echo $data;
            exit(0);
        }
    }
}

foreach ($sw['Files'] as &$f) {
    $sDir = $dir . '/files/' . $sw['ContentID'];
    @mkdir($sDir, 0777, true);
    if (str_starts_with($f['URL'], 'http') || str_starts_with($f['URL'], 'ftp')) {
        $f['URL'] = $localURL . '/rest/dl/' . $sw['ContentID'] . '/' . $f['FileName'];
    }
}
unset($f);

$json = json_encode([$sw], JSON_UNESCAPED_SLASHES);
if ($json === false) {
    $json = '[]';
}
file_put_contents($swFile, $json);
echo $json;
--EXPECT--
