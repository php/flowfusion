--TEST--
Translated seed (inferredbugs-csharp: powershell/2)
--FILE--
<?php
$u = "beta/me";
if (!preg_match('~^https://~i', $u)) {
    if (preg_match('~^/~i', $u)) $u = substr($u, 1);
    if (!preg_match('~^(beta/|v1\.0/)~i', $u)) $u = "v1.0/" . $u;
}
$headers = [];
$consistency = true;
if ($consistency) {
    if ($headers === null) $headers = [];
    unset($headers["ConsistencyLevel"]);
    $headers["ConsistencyLevel"] = "eventual";
}
$content = ["value" => [1, 2], "@odata.nextLink" => "v1.0/me?page=2"];
$contentType = "application/json";
if ($content !== null) {
    if ($contentType === "") $contentType = "application/json";
    $body = is_string($content) ? $content : json_encode($content);
    $parsed = json_decode($body, true);
}
$result = json_encode($content);
$obj = json_decode($result, true);
if (is_array($obj) && isset($obj["value"]) && is_array($obj["value"])) {
    $values = $obj["value"];
    while (true) {
        if (array_key_exists("@odata.nextLink", $obj) && is_string($obj["@odata.nextLink"])) {
            $next = $obj["@odata.nextLink"];
            $result = json_encode(["value" => [3, 4]]);
            $obj = json_decode($result, true);
            if (is_array($obj) && isset($obj["value"]) && is_array($obj["value"])) {
                $values = array_merge($values, $obj["value"]);
            } else {
                break;
            }
        } else {
            break;
        }
    }
}
echo $u;
?>
--EXPECT--
