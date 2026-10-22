--TEST--
Translated seed (inferredbugs-csharp: docusign-esign-csharp-client/1)
--FILE--
<?php
$input = str_repeat("a", 32766) . " /?&=#%" . str_repeat("b", 5) . "é";
echo rawurlencode($input), PHP_EOL;

$scopes = ["signature", "impersonation", "extended"];
echo implode("%20", $scopes), PHP_EOL;

$filename = "C:\\temp\\..\\evil/../safe.txt";
$sanitized = preg_replace('~.*[/\\\\](.*)$~', '$1', $filename);
echo $sanitized, PHP_EOL;

$dt = new DateTimeImmutable("2009-06-15T13:45:30+00:00");
echo $dt->format(DateTimeInterface::ATOM), PHP_EOL;

$items = [1, "2", 3.5, true, null];
echo implode(",", array_map(fn($v) => (string)$v, $items)), PHP_EOL;

$bytes = random_bytes(8);
$stream = fopen("php://memory", "r+");
fwrite($stream, $bytes);
rewind($stream);
echo base64_encode(stream_get_contents($stream)), PHP_EOL;

parse_str("response_type=code&scope=all&client_id=abc&redirect_uri=https%3A%2F%2Fexample.com&state=x", $q);
echo $q["response_type"], " ", $q["scope"], " ", $q["client_id"], PHP_EOL;

echo json_encode(["access_token" => "tok", "token_type" => "Bearer", "expires_in" => 3600], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
