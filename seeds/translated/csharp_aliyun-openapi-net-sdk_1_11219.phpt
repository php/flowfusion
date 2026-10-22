--TEST--
Translated seed (inferredbugs-csharp: aliyun-openapi-net-sdk/1)
--FILE--
<?php
date_default_timezone_set('UTC');

$dt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
echo $dt->format('Y-m-d\TH:i:s\Z') . PHP_EOL;
echo gmdate('D, d M Y H:i:s \G\M\T', $dt->getTimestamp()) . PHP_EOL;
echo strtoupper(bin2hex(md5('test', true))) . PHP_EOL;
echo base64_encode(md5('test', true)) . PHP_EOL;

$f = strtolower('APPLICATION/XML');
echo ($f === 'application/xml' || $f === 'text/xml') ? 'XML' : (($f === 'application/json') ? 'JSON' : (($f === 'application/x-www-form-urlencoded') ? 'FORM' : 'RAW')) . PHP_EOL;

$m = strtoupper('get');
switch ($m) {
    case 'GET':
        echo 'GET';
        break;
    case 'PUT':
        echo 'PUT';
        break;
    case 'POST':
        echo 'POST';
        break;
    case 'DELETE':
        echo 'DELETE';
        break;
    case 'HEAD':
        echo 'HEAD';
        break;
    case 'OPTIONS':
        echo 'OPTIONS';
        break;
    default:
        echo 'NULL';
        break;
}
echo PHP_EOL;
?>
--EXPECT--
