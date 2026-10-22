--TEST--
Translated seed (inferredbugs-csharp: protobuf-csharp-port/1)
--FILE--
<?php
declare(strict_types=1);

$defaultType = 'application/json';
$cases = [
    null,
    '',
    ' application/json; charset=utf-8 ',
    'text/xml; charset=utf-8',
    'application/x-protobuf',
    'application/vnd.google.protobuf',
    'text/javascript',
];

foreach ($cases as $contentType) {
    $type = $contentType ?? '';
    $type = strtolower(trim(explode(';', $type, 2)[0]));
    if ($type === '' && $defaultType !== '') {
        $type = strtolower(trim(explode(';', $defaultType, 2)[0]));
    }

    if ($type === 'application/json' || $type === 'application/x-json' || $type === 'application/x-javascript' || $type === 'text/javascript' || $type === 'text/x-javascript' || $type === 'text/x-json' || $type === 'text/json') {
        $decoded = json_decode("{\"a\":1,\"b\":[true,false,null],\"s\":\"x\\u0000y\"}", true);
        $encoded = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        echo $encoded, "\n";
    } elseif ($type === 'text/xml' || $type === 'application/xml') {
        $xml = new SimpleXMLElement('<root/>');
        $xml->addChild('a', '1');
        $xml->addChild('b', 'x & y');
        echo $xml->asXML(), "\n";
    } elseif ($type === 'application/binary' || $type === 'application/x-protobuf' || $type === 'application/vnd.google.protobuf') {
        $data = pack('C*', 0, 255, 1, 2, 127);
        echo bin2hex($data), "\n";
    } else {
        throw new OutOfRangeException('contentType');
    }
}
?>
--EXPECT--
