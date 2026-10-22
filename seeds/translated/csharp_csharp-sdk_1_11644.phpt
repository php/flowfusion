--TEST--
Translated seed (inferredbugs-csharp: csharp-sdk/1)
--FILE--
<?php
$chunkBits = 22;
$chunkSize = 1 << $chunkBits;

$fsize = (1 << 22) + 3;
$chunkCnt = (int)(($fsize + ($chunkSize - 1)) >> $chunkBits);
$ctxs = [];
for ($i = 0; $i < $chunkCnt; $i++) {
    $curSize = ($i === $chunkCnt - 1) ? ($fsize - ($i << $chunkBits)) : $chunkSize;
    $ctxs[] = str_repeat(chr(65 + $i), (int)$curSize);
}

$entryURI = "table:key";
$url = "https://up.example/rs-mkfile/" . rtrim(strtr(base64_encode($entryURI), '+/', '-_'), '=') . "/fsize/" . $fsize;
$callbackParam = "a=b&c=d";
$customMeta = "x:y";
if ($callbackParam !== '') $url .= "/params/" . $callbackParam;
if ($customMeta !== '') $url .= "/meta/" . $customMeta;

$body = '';
for ($i = 0; $i < count($ctxs); $i++) {
    $body .= $ctxs[$i];
    if ($i !== count($ctxs) - 1) $body .= ',';
}

echo $url, "\n", strlen($body), "\n";
?>
--EXPECT--
