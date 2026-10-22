--TEST--
Translated seed (inferredbugs-csharp: dotnet-standard-sdk/8)
--FILE--
<?php
$versionDate = '2018-03-19';
$endpoint = 'https://gateway.watsonplatform.net/visual-recognition/api';
$boundary = '----phpform' . bin2hex(random_bytes(8));
$form = '';

$form .= "--$boundary\r\n";
$form .= "Content-Disposition: form-data; name=\"name\"\r\n\r\n";
$form .= "test\r\n";

foreach (['cat', 'dog'] as $k) {
    $form .= "--$boundary\r\n";
    $form .= "Content-Disposition: form-data; name=\"{$k}_positive_examples\"; filename=\"{$k}.bin\"\r\n";
    $form .= "Content-Type: application/octet-stream\r\n\r\n";
    $form .= str_repeat($k, 3) . "\r\n";
}

$form .= "--$boundary\r\n";
$form .= "Content-Disposition: form-data; name=\"negative_examples\"; filename=\"neg.bin\"\r\n";
$form .= "Content-Type: application/octet-stream\r\n\r\n";
$form .= "negdata\r\n";
$form .= "--$boundary--\r\n";

$ch = curl_init($endpoint . '/v3/classifiers?version=' . rawurlencode($versionDate));
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: multipart/form-data; boundary=' . $boundary],
    CURLOPT_POSTFIELDS => $form,
    CURLOPT_TIMEOUT => 1,
]);
curl_exec($ch);
curl_close($ch);

echo "ok\n";
?>
--EXPECT--
