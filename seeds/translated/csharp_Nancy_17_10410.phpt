--TEST--
Translated seed (inferredbugs-csharp: Nancy/17)
--FILE--
<?php
$bodyContent = "name=John+Doe&gender=male&family=5&city=kent&city=miami&other=abc%0D%0Adef&nickname=J%26D";
$memory = fopen('php://temp', 'r+');
fwrite($memory, $bodyContent);
rewind($memory);

$headers = ['content-type' => ['x-www-form-urlencoded']];

$method = 'POST';
$uri = '/';

if ($method === null || $method === '') {
    throw new Exception('invalid method');
}
if ($uri === null || $uri === '') {
    throw new Exception('invalid uri');
}
if ($headers === null || $memory === null) {
    throw new Exception('invalid request');
}

parse_str(stream_get_contents($memory), $form);

if (($form['name'] ?? null) !== 'John Doe') {
    throw new Exception('bad form parse');
}
?>
--EXPECT--
