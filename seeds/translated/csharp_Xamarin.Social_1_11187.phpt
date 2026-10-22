--TEST--
Translated seed (inferredbugs-csharp: Xamarin.Social/1)
--FILE--
<?php
$u = 'http://example.com/path?x=1';
$p = ['a' => '1 2', 'b' => 'x&y', 'c' => "ü"];
$q = http_build_query($p, '', '&', PHP_QUERY_RFC3986);
$u .= (strpos($u, '?') !== false ? '&' : '?') . $q;

$boundary = '---------------------------' . mt_rand();
$parts = [
    ['name' => 'text', 'data' => "hello\r\nworld", 'mime' => '', 'filename' => ''],
    ['name' => 'file', 'data' => "abc\0def", 'mime' => 'application/octet-stream', 'filename' => 'f.txt'],
];

$body = '';
foreach ($parts as $part) {
    $body .= "--$boundary\r\n";
    $body .= 'Content-Disposition: form-data; name="' . $part['name'] . '"';
    if ($part['filename'] !== '') $body .= '; filename="' . $part['filename'] . '"';
    $body .= "\r\n";
    if ($part['mime'] !== '') $body .= 'Content-Type: ' . $part['mime'] . "\r\n";
    $body .= "\r\n";
    $body .= $part['data'] . "\r\n";
}
$body .= "--$boundary--\r\n";

echo $u, "\n", $body;
?>
--EXPECT--
