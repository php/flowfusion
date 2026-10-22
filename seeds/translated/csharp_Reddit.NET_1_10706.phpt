--TEST--
Translated seed (inferredbugs-csharp: Reddit.NET/1)
--FILE--
<?php
$accessToken = "x";
$refreshToken = "r";
$requests = [];

$prepareRequest = function(array $request) use (&$accessToken) {
    $request["headers"]["Authorization"] = "bearer " . $accessToken;
    return $request;
};

$execute = function(array $request) use (&$requests, &$accessToken, $refreshToken, $prepareRequest) {
    $requests[] = $request;
    static $count = 0;
    $count++;

    if ($count === 1) {
        return ["success" => false, "status" => 401, "content" => null];
    }

    if ($count === 2 && $refreshToken !== null) {
        $keyRes = ["success" => true, "content" => json_encode(["access_token" => "newtoken"])];
        if ($keyRes !== null && $keyRes["success"]) {
            $tokenData = json_decode($keyRes["content"], true);
            $accessToken = (string)$tokenData["access_token"];
            $request = $prepareRequest($request);
        }
    }

    $requests[] = $request;
    return ["success" => true, "status" => 200, "content" => "ok"];
};

$request = ["resource" => "/x", "method" => "GET", "headers" => [], "parameters" => [["name" => "Authorization", "value" => "bad"], ["name" => "a", "value" => "1"]]];
$request = $prepareRequest($request);

foreach ($request["parameters"] as $param) {
    if (strcasecmp($param["name"], "Authorization") !== 0) {
        $filtered[] = $param;
    }
}
$request["parameters"] = $filtered ?? [];

$res = $execute($request);
$retry = 5;

while ((!$res || !$res["success"]) && $retry > 0) {
    if ($refreshToken !== null && in_array($res["status"] ?? null, [400, 401, 403], true)) {
        $keyRes = ["success" => true, "content" => json_encode(["access_token" => "newtoken"])];
        if ($keyRes !== null && $keyRes["success"]) {
            $tokenData = json_decode($keyRes["content"], true);
            $accessToken = (string)$tokenData["access_token"];
            $request = $prepareRequest($request);
        }
    }

    $res = $execute($request);
    $retry--;
}

echo $res && $res["success"] ? $res["content"] : "";
?>
--EXPECT--
