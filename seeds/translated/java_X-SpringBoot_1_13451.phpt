--TEST--
Translated seed (inferredbugs-java: X-SpringBoot/1)
--FILE--
<?php

$authentication = ['principal' => ['username' => 'user']];
$tokenExpire = 3600;
$authenticationToken = 'AUTHENTICATION_TOKEN';

function md5Secure($string) {
    return md5($string);
}

function serializeData($data) {
    return serialize($data);
}

$token = isset($authentication['principal']['username']) ? md5Secure($authentication['principal']['username'] . microtime(true)) : md5Secure(microtime(true));

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->set($authenticationToken, $token, $tokenExpire);

$authenticationKey = serializeData($token);
$authenticationValue = serializeData($authentication);

$redis->multi(Redis::PIPELINE);
$redis->set($authenticationKey, $authenticationValue);
$redis->exec();

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['status' => 'ok', 'token' => $token]);
--EXPECT--
