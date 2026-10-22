--TEST--
Translated seed (inferredbugs-csharp: docusign-esign-csharp-client/3)
--FILE--
<?php
$u = [];
$u[] = rawurlencode(str_repeat("a", 32766));
$u[] = rawurlencode(str_repeat("b", 32767));
$u[] = implode(",", ["x", "y", "z"]);
$u[] = base64_encode("test");
$u[] = json_encode(["k" => "v", "n" => 1]);
$u[] = preg_replace('~.*[/\\\\](.*)$~', '$1', "dir/sub\\file.txt");
$u[] = http_build_query([
    "response_type" => "code",
    "scope" => "all",
    "client_id" => "id",
    "redirect_uri" => "https://example.com/cb",
    "state" => "s"
], '', '&', PHP_QUERY_RFC3986);
$u[] = http_build_query([
    "grant_type" => "authorization_code",
    "code" => "abc"
], '', '&', PHP_QUERY_RFC3986);

$tmp = tempnam(sys_get_temp_dir(), "p");
file_put_contents($tmp, "hello");
$f = fopen($tmp, "rb");
$bytes = stream_get_contents($f);
fclose($f);
unlink($tmp);

$h = fopen("php://temp", "r+");
fwrite($h, $bytes);
rewind($h);
stream_get_contents($h);
fclose($h);

echo implode("\n", $u), "\n";
?>
--EXPECT--
