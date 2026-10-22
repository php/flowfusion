--TEST--
Translated seed (inferredbugs-csharp: powershell/1)
--FILE--
<?php
$urls = ["https://example.com/x", "/beta/items", "items", "v1.0/users", "/v1.0/groups"];
$headers = null;
$consistencyLevelEventual = true;

foreach ($urls as $u) {
    $url = $u;
    if (stripos($url, "https://") !== 0) {
        if (stripos($url, "/") === 0) {
            $url = substr($url, 1);
        }
        if (stripos($url, "beta/") !== 0 && stripos($url, "v1.0/") !== 0) {
            $url = "v1.0/" . $url;
        }
    }

    if ($consistencyLevelEventual) {
        if ($headers === null) {
            $headers = [];
        }
        unset($headers["ConsistencyLevel"]);
        $headers["ConsistencyLevel"] = "eventual";
    }

    $content = ["url" => $url, "headers" => $headers, "items" => [1, 2, 3]];
    $json = json_encode($content, JSON_UNESCAPED_SLASHES);
    $decoded = json_decode($json, true);

    if (is_array($decoded) && isset($decoded["items"]) && is_array($decoded["items"])) {
        $decoded["items"][] = count($decoded["items"]);
    }

    $paged = '{"value":[1,2],"@odata.nextLink":"https://example.com/next"}';
    $obj = json_decode($paged, true);
    if (is_array($obj) && isset($obj["value"]) && is_array($obj["value"])) {
        while (isset($obj["@odata.nextLink"]) && is_string($obj["@odata.nextLink"])) {
            $next = '{"value":[3,4]}';
            $nextObj = json_decode($next, true);
            if (is_array($nextObj) && isset($nextObj["value"]) && is_array($nextObj["value"])) {
                $obj["value"] = array_merge($obj["value"], $nextObj["value"]);
            } else {
                break;
            }
            unset($obj["@odata.nextLink"]);
        }
    }

    echo $json . "\n";
}
?>
--EXPECT--
