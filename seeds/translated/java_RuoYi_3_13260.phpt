--TEST--
Translated seed (inferredbugs-java: RuoYi/3)
--FILE--
<?php

$user = [
    'loginName' => 'testUser',
    'password' => 'oldPassword',
    'salt' => 'randomSalt'
];

function md5Hash($loginName, $password, $salt) {
    return md5($loginName . $password . $salt);
}

$passwordToCheck = 'oldPassword';
$encrypt = md5Hash($user['loginName'], $passwordToCheck, $user['salt']);

if ($user['password'] === $encrypt) {
    echo "Password is correct";
} else {
    echo "Password is incorrect";
}

$user['newPassword'] = 'newPassword';
$user['salt'] = 'newRandomSalt';
$user['password'] = md5Hash($user['loginName'], $user['newPassword'], $user['salt']);

if ($user['password'] === md5Hash($user['loginName'], $user['newPassword'], $user['salt'])) {
    echo "Password reset successful";
}
--EXPECT--
