--TEST--
Translated seed (inferredbugs-csharp: Nancy/1)
--FILE--
<?php
declare(strict_types=1);

$cases = [
    [null, '/', [], fopen('php://memory', 'r+')],
    ['', '/', [], fopen('php://memory', 'r+')],
    ['GET', null, [], fopen('php://memory', 'r+')],
    ['GET', '', [], fopen('php://memory', 'r+')],
    ['GET', '/', null, fopen('php://memory', 'r+')],
    ['GET', '/', [], null],
];

foreach ($cases as $case) {
    try {
        [$method, $uri, $headers, $body] = $case;
        if ($method === null) {
            throw new InvalidArgumentException('method');
        }
        if ($method === '') {
            throw new OutOfRangeException('method');
        }
        if ($uri === null) {
            throw new InvalidArgumentException('uri');
        }
        if ($uri === '') {
            throw new OutOfRangeException('uri');
        }
        if ($headers === null) {
            throw new InvalidArgumentException('headers');
        }
        if ($body === null) {
            throw new InvalidArgumentException('body');
        }
    } catch (Throwable $e) {
    }
}

$method = 'GET';
$uri = '/';
$headers = [];
$body = fopen('php://memory', 'r+');
$request = (object)['Method' => $method, 'Uri' => $uri, 'Headers' => $headers, 'Body' => $body];

if ($request->Method !== $method || $request->Uri !== $uri || $request->Headers !== $headers || $request->Body !== $body) {
    throw new RuntimeException('request mismatch');
}

$bodyContent = 'name=John+Doe&gender=male&family=5&city=kent&city=miami&other=abc%0D%0Adef&nickname=J%26D';
$memory = fopen('php://memory', 'r+');
fwrite($memory, $bodyContent);
rewind($memory);
$headers = ['content-type' => ['x-www-form-urlencoded']];
parse_str(stream_get_contents($memory), $form);

if (($form['name'] ?? null) !== 'John Doe') {
    throw new RuntimeException('form parse mismatch');
}
?>
--EXPECT--
