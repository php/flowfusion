--TEST--
Translated seed (inferredbugs-csharp: Reddit.NET/3)
--FILE--
<?php
$accessToken = "token";
$refreshToken = "refresh";
$appId = "app";

$prepareRequest = function (array $req) use (&$accessToken) {
    $req["headers"]["Authorization"] = "bearer " . $accessToken;
    if ($req["method"] === "POST" || $req["method"] === "PUT") {
        $req["headers"]["Content-Type"] = "application/x-www-form-urlencoded";
    }
    return $req;
};

$executeRequest = function (array $req) use (&$accessToken, $refreshToken, $appId, $prepareRequest) {
    $retry = 5;
    $res = ["ok" => false, "code" => 401, "content" => null];

    while ((!$res["ok"]) && $retry > 0) {
        if ($refreshToken !== null && ($res["code"] === 400 || $res["code"] === 401)) {
            $accessToken = "newtoken";
            $req = $prepareRequest($req);
        }

        if ($accessToken === "newtoken") {
            $res = ["ok" => true, "code" => 200, "content" => "ok"];
        } else {
            $res = ["ok" => false, "code" => 401, "content" => null];
        }

        $retry--;
    }

    if ($res === null) {
        throw new Exception("Reddit API returned null response.");
    } elseif (!$res["ok"]) {
        throw new Exception("Reddit API returned non-success response.");
    }

    return $res["content"];
};

$req = ["resource" => "/api", "method" => "POST", "headers" => [], "parameters" => [["Name" => "x", "Value" => "y"]]];
$req = $prepareRequest($req);
$executeRequest($req);
echo "done\n";
?>
--EXPECT--
