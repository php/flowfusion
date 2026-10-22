--TEST--
Translated seed (inferredbugs-csharp: RedditSharp/1)
--FILE--
<?php
$boundary = '----------' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 10);
$buf = '';
$addString = function($name, $value) use (&$buf, $boundary) {
    $buf .= "--$boundary\r\nContent-Disposition: form-data; name=\"$name\"\r\n\r\n$value\r\n";
};
$addFile = function($name, $filename, $value, $contentType) use (&$buf, $boundary) {
    $buf .= "--$boundary\r\nContent-Disposition: form-data; name=\"$name\"; filename=\"$filename\"\r\nContent-Type: $contentType\r\n\r\n";
    $buf .= $value . "\r\n";
};

$obj = new class {
    public $a = "0";
    public $b = 123;
    public $c = null;
};
foreach (get_object_vars($obj) as $name => $value) {
    $addString($name, (string)$value);
}
$addFile('file', 'x"y\r\nz', "\0\xff\r\n--", 'application/octet-stream');
$buf .= "--$boundary--";
echo $buf;
?>
--EXPECT--
