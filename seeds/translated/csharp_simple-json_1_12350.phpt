--TEST--
Translated seed (inferredbugs-csharp: simple-json/1)
--FILE--
<?php
$input = "{\n  \"CPU\": \"Intel\",\n  \"Drives\": [\n    \"DVD read/writer\",\n    \"500 gigabyte hard drive\"\n  ]\n}";
$obj = json_decode($input, true, 512, JSON_THROW_ON_ERROR);
if (!is_array($obj) || count($obj) !== 2 || $obj["CPU"] !== "Intel" || !is_array($obj["Drives"]) || count($obj["Drives"]) !== 2 || $obj["Drives"][0] !== "DVD read/writer" || $obj["Drives"][1] !== "500 gigabyte hard drive") {
    exit(1);
}

$result = json_decode("\"h\0i\"", true, 512, JSON_THROW_ON_ERROR);
if ($result !== "h\0i") {
    exit(1);
}

$result = json_decode("{\"hi\r\nbye\":1}", true, 512, JSON_THROW_ON_ERROR);
foreach ($result as $k => $v) {
    if ($k !== "hi\nbye") {
        exit(1);
    }
}

$input = "{\n  \"CPU\": \"Intel\",\n  \"Drives\": [ \n    \"DVD read/writer\",\n    \"500 gigabyte hard drive\"\n  ]\n]\n";
json_decode($input, true);
if (json_last_error() === JSON_ERROR_NONE) {
    exit(1);
}

$length = 20000;
$json = "[\"" . str_repeat(" ", $length) . "\"]";
$o = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
if (!is_array($o) || strlen($o[0]) !== 20000) {
    exit(1);
}

$json = "[\"\\u003c\",\"\\u5f20\"]";
$o = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
if (!is_array($o) || count($o) !== 2 || $o[0] !== "<" || $o[1] !== "张") {
    exit(1);
}

$json = "{\"Message\":\"Hi,I\\u0092ve send you smth\"}";
$o = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
if (!is_array($o) || $o["Message"] !== "Hi,I\x92ve send you smth") {
    exit(1);
}

json_decode("[1", true);
if (json_last_error() === JSON_ERROR_NONE) {
    exit(1);
}

echo "";
?>
--EXPECT--
