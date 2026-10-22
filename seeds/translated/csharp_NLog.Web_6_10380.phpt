--TEST--
Translated seed (inferredbugs-csharp: NLog.Web/6)
--FILE--
<?php
$body = fopen('php://temp', 'r+');
fwrite($body, "username=xyz&password=xyz");
rewind($body);
$oldPosition = ftell($body);
rewind($body);
$content = stream_get_contents($body);
if (is_resource($body)) {
    fseek($body, $oldPosition);
}
echo $content;
?>
--EXPECT--
