--TEST--
Translated seed (inferredbugs-java: MicroCommunity/8)
--FILE--
<?php

$paramIn = '{"userId":"1","oldPwd":"password123","newPwd":"newPassword456"}';
$paramInJson = json_decode($paramIn, true);

if (!isset($paramInJson['userId']) || !isset($paramInJson['oldPwd']) || !isset($paramInJson['newPwd'])) {
    exit("Invalid parameters");
}

$userInfo = json_decode('{"userId":"1","password":"password123"}', true);
if ($paramInJson['oldPwd'] !== $userInfo['password']) {
    exit("Original password is incorrect");
}

$userInfo['password'] = $paramInJson['newPwd'];

$business = [
    'datas' => [],
    'businessTypeCd' => 'modify_user_info',
    'seq' => 1,
    'invokeModel' => 'S',
];

$business['datas']['businessUser'] = $userInfo;

$header = [
    'User-ID' => $paramInJson['userId'],
    'Order-Type-Cd' => 'D',
];

$paramInObj = json_encode($business);
$requestUrl = 'http://user-service/test/sayHello';

$options = [
    'http' => [
        'header' => "Content-Type: application/json\r\n" .
                    "User-ID: " . $header['User-ID'] . "\r\n" .
                    "Order-Type-Cd: " . $header['Order-Type-Cd'] . "\r\n",
        'method' => 'POST',
        'content' => $paramInObj,
    ],
];

$context = stream_context_create($options);
$response = file_get_contents($requestUrl, false, $context);

if ($response === FALSE) {
    exit("Request failed");
}
--EXPECT--
