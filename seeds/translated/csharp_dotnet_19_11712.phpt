--TEST--
Translated seed (inferredbugs-csharp: dotnet/19)
--FILE--
<?php
$u = "https://api.structurizr.com";
if ($u === null || trim($u) === "") {
    throw new InvalidArgumentException("The API URL must not be null or empty.");
}
if (str_ends_with($u, "/")) {
    $u = substr($u, 0, -1);
}
$k = "key";
if ($k === null || trim($k) === "") {
    throw new InvalidArgumentException("The API key must not be null or empty.");
}
$s = "secret";
if ($s === null || trim($s) === "") {
    throw new InvalidArgumentException("The API secret must not be null or empty.");
}
$workspaceId = 1;
if ($workspaceId <= 0) {
    throw new InvalidArgumentException("The workspace ID must be a positive integer.");
}
$path = "/workspace/" . $workspaceId;
$nonce = (string) (int) floor(microtime(true) * 1000);
$content = "";
$md5 = base64_encode(md5($content, true));
$hmac = hash_hmac("sha256", "GET\n" . $path . "\n" . $md5 . "\n\n" . $nonce, $s);
$auth = "structurizr key=" . $k . ",signature=" . $hmac;
$headers = [
    "X-Authorization: " . $auth,
    "User-Agent: structurizr-php",
    "Nonce: " . $nonce,
];
$ctx = stream_context_create([
    "http" => [
        "method" => "GET",
        "header" => implode("\r\n", $headers),
        "ignore_errors" => true,
        "timeout" => 1,
    ],
]);
@file_get_contents($u . $path, false, $ctx);
echo "";
?>
--EXPECT--
