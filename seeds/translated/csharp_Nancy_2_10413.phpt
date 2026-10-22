--TEST--
Translated seed (inferredbugs-csharp: Nancy/2)
--FILE--
<?php
class Request {
    public $Method;
    public $Uri;
    public $Headers;
    public $Body;
    public $Form = [];

    function __construct($method, $uri, $headers, $body) {
        if ($method === null) throw new InvalidArgumentException();
        if ($method === '') throw new OutOfRangeException();
        if ($uri === null) throw new InvalidArgumentException();
        if ($uri === '') throw new OutOfRangeException();
        if ($headers === null) throw new InvalidArgumentException();
        if ($body === null) throw new InvalidArgumentException();
        $this->Method = $method;
        $this->Uri = $uri;
        $this->Headers = $headers;
        $this->Body = $body;
        if (isset($headers['content-type']) && in_array('x-www-form-urlencoded', $headers['content-type'], true)) {
            $this->Form = [];
            parse_str(stream_get_contents($body), $this->Form);
        }
    }
}

$headers = [];
$body = fopen('php://memory', 'r+');
fwrite($body, 'name=John+Doe&gender=male&family=5&city=kent&city=miami&other=abc%0D%0Adef&nickname=J%26D');
rewind($body);
$r = new Request('POST', '/', ['content-type' => ['x-www-form-urlencoded']], $body);
if ($r->Method !== 'POST' || $r->Uri !== '/' || $r->Headers['content-type'][0] !== 'x-www-form-urlencoded' || $r->Body !== $body || $r->Form['name'] !== 'John Doe' || $r->Form['city'][0] !== 'kent' || $r->Form['city'][1] !== 'miami' || $r->Form['other'] !== "abc\r\ndef" || $r->Form['nickname'] !== 'J&D') {
    exit(1);
}
?>
--EXPECT--
