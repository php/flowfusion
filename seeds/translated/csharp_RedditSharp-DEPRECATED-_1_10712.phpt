--TEST--
Translated seed (inferredbugs-csharp: RedditSharp-DEPRECATED-/1)
--FILE--
<?php
$boundary = '----------';
$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
for ($i = 0; $i < 10; $i++) {
    $boundary .= $chars[random_int(0, strlen($chars) - 1)];
}

$buffer = fopen('php://temp', 'r+');

fwrite($buffer, "--$boundary\r\nContent-Disposition: form-data; name=\"field\"\r\n\r\nvalue\r\n");
fwrite($buffer, "--$boundary\r\nContent-Disposition: form-data; name=\"file\"; filename=\"a.txt\"\r\nContent-Type: text/plain\r\n\r\n");
fwrite($buffer, "abc\0def");
fwrite($buffer, "\r\n");
fwrite($buffer, "--$boundary--");

rewind($buffer);
stream_get_contents($buffer);
fclose($buffer);
?>
--EXPECT--
