--TEST--
Translated seed (inferredbugs-csharp: simple-json/2)
--FILE--
<?php
$input = "{\n  \"CPU\": \"Intel\",\n  \"Drives\": [\n    \"DVD read/writer\",\n    \"500 gigabyte hard drive\"\n  ]\n}";
$data = json_decode($input, true, 512, JSON_THROW_ON_ERROR);
if (!is_array($data) || count($data) !== 2 || $data['CPU'] !== 'Intel' || !is_array($data['Drives']) || count($data['Drives']) !== 2 || $data['Drives'][0] !== 'DVD read/writer' || $data['Drives'][1] !== '500 gigabyte hard drive') {
    throw new RuntimeException('bad decode');
}

$tests = [
    "\"h\0i\"" => "h\0i",
    "{\n  \"CPU\": \"Intel\",\n  \"Drives\": [ \n    \"DVD read/writer\",\n    \"500 gigabyte hard drive\"\n  ]\n}\n" => null,
    "[\n  NaN,\n  Infinity,\n  -Infinity\n]" => null,
    "[\"" . str_repeat(' ', 20000) . "\"]" => str_repeat(' ', 20000),
    "[\"\\u003c\",\"\\u5f20\"]" => ['<', '张'],
    "{\"Message\":\"Hi,I\\u0092ve send you smth\"}" => "Hi,I\x92ve send you smth",
];

foreach ($tests as $json => $expected) {
    $v = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    if ($expected !== null) {
        if (is_array($expected)) {
            if ($v !== $expected) {
                throw new RuntimeException('bad value');
            }
        } elseif ($v[0] !== $expected && $v !== $expected) {
            throw new RuntimeException('bad value');
        }
    }
}

foreach ([
    'hi',
    "'h\\u006",
    "'h\\",
    "{aww",
    "{\n    \"A\" : true,\n    \"B\" \"hello\",\n    \"C\" : \"bye\"\n}",
    "[1",
] as $json) {
    try {
        json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        throw new RuntimeException('expected failure');
    } catch (JsonException $e) {
    }
}

$json = "{\n    \"A\" : true,\n    \"B\" : \"hello\",\n    \"C\" : \"bye\"\n}";
$data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
if ($data['B'] !== 'hello') {
    throw new RuntimeException('bad value');
}

$json = "{\"text\":0xabcdef12345}";
try {
    json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    throw new RuntimeException('expected failure');
} catch (JsonException $e) {
}
?>
--EXPECT--
