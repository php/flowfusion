--TEST--
Translated seed (inferredbugs-java: RuoYi/4)
--FILE--
<?php

$prefix = "system/user/profile";

function getUser() {
    return (object)[
        'loginName' => 'testUser',
        'salt' => 'randomSalt',
        'password' => '5f4dcc3b5aa765d61d8327deb882cf99' // md5 of "password"
    ];
}

function md5Hash($data) {
    return md5($data);
}

function checkPassword($password) {
    $user = getUser();
    $encrypt = md5Hash($user->loginName . $password . $user->salt);
    return $user->password === $encrypt;
}

$userId = 1; // Dummy user id for testing
$passwordToCheck = 'password';

if (checkPassword($passwordToCheck)) {
    echo "Password is correct.";
} else {
    echo "Password is incorrect.";
}
--EXPECT--
