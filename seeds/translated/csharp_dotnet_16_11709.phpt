--TEST--
Translated seed (inferredbugs-csharp: dotnet/16)
--FILE--
<?php
$u = "https://api.structurizr.com";
if ($u === null || trim($u) === "") throw new InvalidArgumentException("The API URL must not be null or empty.");
if (str_ends_with($u, "/")) $u = substr($u, 0, -1);

$k = "key";
if ($k === null || trim($k) === "") throw new InvalidArgumentException("The API key must not be null or empty.");

$s = "secret";
if ($s === null || trim($s) === "") throw new InvalidArgumentException("The API secret must not be null or empty.");

$workspaceId = 1;
if ($workspaceId <= 0) throw new InvalidArgumentException("The workspace ID must be a positive integer.");

$workspace = ["id" => 0, "lastModifiedDate" => null, "views" => ["configuration" => []]];
$remote = ["views" => ["configuration" => ["layout" => "x"]]];
$workspace["views"] = array_replace_recursive($workspace["views"], $remote["views"]);
$workspace["id"] = $workspaceId;
$workspace["lastModifiedDate"] = gmdate("c");

$json = json_encode($workspace, JSON_UNESCAPED_SLASHES);
$md5 = base64_encode(md5($json, true));
$nonce = (string) (int) floor(microtime(true) * 1000);
$path = "/workspace/" . $workspaceId;
$auth = "HMAC " . $k . ":" . hash_hmac("sha256", "GET" . $path . $md5 . "" . $nonce, $s);
$headers = [
    "X-Authorization: " . $auth,
    "User-Agent: structurizr-php",
    "Nonce: " . $nonce,
    "Content-MD5: " . $md5,
];
echo $json;
?>
--EXPECT--
