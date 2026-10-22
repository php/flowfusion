--TEST--
Translated seed (inferredbugs-csharp: docusign-esign-csharp-client/4)
--FILE--
<?php
$basePath = 'https://www.docusign.net/restapi';
$long = str_repeat('a', 40000) . ' /?%#&+=' . str_repeat('b', 40000);
echo rawurlencode($long), "\n";
echo base64_encode("DocuSign\0test"), "\n";
echo implode(',', ['x', 1, true, null, 'y']), "\n";
echo http_build_query([
    'response_type' => 'code',
    'scope' => 'all',
    'client_id' => 'id',
    'redirect_uri' => 'https://example.com/callback',
    'state' => 's t a t e',
], '', '&', PHP_QUERY_RFC3986), "\n";
$filename = 'folder/subfolder/../evil.txt';
$sanitized = preg_replace('~.*[\\\\/](.*)$~', '$1', $filename);
echo $sanitized, "\n";
$tmp = tempnam(sys_get_temp_dir(), 'pht');
file_put_contents($tmp, "abc");
$h = fopen($tmp, 'rb');
$data = stream_get_contents($h);
fclose($h);
unlink($tmp);
echo $data, "\n";
$bytes = random_bytes(16);
echo strlen($bytes), "\n";
?>
--EXPECT--
