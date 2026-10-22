--TEST--
Translated seed (inferredbugs-java: blynk-server/20)
--FILE--
<?php

class FlashedToken {
    public $token;
    public $appName;
    public $deviceId;

    public function __construct($token, $appName, $deviceId) {
        $this->token = $token;
        $this->appName = $appName;
        $this->deviceId = $deviceId;
    }
}

function generateTokens($count, $appName, $deviceCount) {
    $flashedTokens = [];

    for ($deviceId = 0; $deviceId < $deviceCount; $deviceId++) {
        for ($i = 0; $i < $count; $i++) {
            $token = str_replace('-', '', uuid_create(UUID_TYPE_RANDOM));
            $flashedTokens[] = new FlashedToken($token, $appName, $deviceId);
            echo "Token : $token, deviceId : $deviceId, appName : $appName\n";
        }
    }
    return $flashedTokens;
}

$count = 10;
$appName = "BLYNK";
$deviceCount = 1;
$flashedTokens = generateTokens($count, $appName, $deviceCount);
--EXPECT--
