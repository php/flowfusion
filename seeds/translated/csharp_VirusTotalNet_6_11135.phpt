--TEST--
Translated seed (inferredbugs-csharp: VirusTotalNet/6)
--FILE--
<?php
$apiKey = str_repeat('a', 64);
$restrictSizeLimits = true;
$fileSizeLimit = 33553369;
$commentSizeRestriction = 4096;
$rescanBatchSizeLimit = 25;
$fileReportBatchSizeLimit = 4;
$urlReportBatchSizeLimit = 4;
$urlScanBatchSizeLimit = 25;

$stream = fopen('php://temp', 'r+');
fwrite($stream, "x");
rewind($stream);
$len = fstat($stream)['size'];

if ($restrictSizeLimits && $len > $fileSizeLimit) {
    exit;
}

$resourceList = [];
for ($i = 0; $i < $rescanBatchSizeLimit; $i++) {
    $resourceList[] = hash('sha256', "file$i");
}

$urlList = [];
for ($i = 0; $i < $urlScanBatchSizeLimit; $i++) {
    $urlList[] = "https://example.com/path/$i?q=" . rawurlencode((string)$i);
}

$comment = str_repeat("c", $commentSizeRestriction);
$payload = [
    'apikey' => $apiKey,
    'resource' => implode(',', $resourceList),
    'url' => implode("\n", $urlList),
    'comment' => $comment,
    'file_size' => $len,
];

$json = json_encode($payload, JSON_UNESCAPED_SLASHES);
$decoded = json_decode($json, true);

$hash = hash('sha256', $decoded['resource'] . $decoded['url'] . $decoded['comment']);
echo substr($hash, 0, 16), "\n";
?>
--EXPECT--
