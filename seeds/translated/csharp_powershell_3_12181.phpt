--TEST--
Translated seed (inferredbugs-csharp: powershell/3)
--FILE--
<?php
$url = "beta/users";
if (!str_starts_with($url, "https://")) {
    if (str_starts_with($url, "/")) $url = substr($url, 1);
    if (!str_starts_with($url, "beta/") && !str_starts_with($url, "v1.0/")) $url = "v1.0/" . $url;
}

$headers = [];
$consistencyLevelEventual = true;
if ($consistencyLevelEventual) {
    if ($headers === null) $headers = [];
    unset($headers["ConsistencyLevel"]);
    $headers["ConsistencyLevel"] = "eventual";
}

$raw = false;
$all = true;

$result = '{"value":[{"id":1}],"@odata.nextLink":"v1.0/users?page=2"}';
if ($raw) {
    echo $result;
} else {
    $rootObj = json_decode($result, true);
    if ($all && $rootObj !== null && isset($rootObj["value"]) && is_array($rootObj["value"])) {
        $values = &$rootObj["value"];
        while (true) {
            if (array_key_exists("@odata.nextLink", $rootObj) && is_string($rootObj["@odata.nextLink"])) {
                $nextLink = $rootObj["@odata.nextLink"];
                if ($nextLink === "v1.0/users?page=2") {
                    $result = '{"value":[{"id":2},{"id":3}]}';
                } else {
                    break;
                }
                $nextObj = json_decode($result, true);
                if ($nextObj !== null && isset($nextObj["value"]) && is_array($nextObj["value"])) {
                    $values = array_merge($values, $nextObj["value"]);
                    $rootObj = $nextObj;
                } else {
                    break;
                }
            } else {
                break;
            }
        }
    }
    json_encode($rootObj);
}
?>
--EXPECT--
