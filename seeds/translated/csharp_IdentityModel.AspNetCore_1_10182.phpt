--TEST--
Translated seed (inferredbugs-csharp: IdentityModel.AspNetCore/1)
--FILE--
<?php
$uri = 'https://example.com/path?x=1';
$headers = [
    'Host' => 'example.com',
    'X-Test' => '1',
];
$content = "payload";
$properties = [
    'method' => 'POST',
    'timeout' => 30,
];

$capturedAddress = null;
$capturedContent = null;
$capturedProperties = null;

$capturedAddress = $uri;
$capturedContent = $content;
$capturedProperties = $properties;

$responseCode = 404;
echo $capturedAddress, "\n";
echo $capturedContent, "\n";
echo json_encode($capturedProperties), "\n";
echo $responseCode, "\n";
?>
--EXPECT--
