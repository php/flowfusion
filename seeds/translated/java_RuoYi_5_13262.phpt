--TEST--
Translated seed (inferredbugs-java: RuoYi/5)
--FILE--
<?php

class SysUser {
    public $userId;
    public $loginName;
    public $password;
    public $salt;
    public $avatar;

    public function __construct($userId, $loginName, $password, $salt) {
        $this->userId = $userId;
        $this->loginName = $loginName;
        $this->password = $password;
        $this->salt = $salt;
    }
}

function getUser() {
    return new SysUser(1, 'testUser', '5f4dcc3b5aa765d61d8327deb882cf99', 'randomSalt'); // password is 'password'
}

function encryptPassword($loginName, $password, $salt) {
    return md5($loginName . $password . $salt);
}

function resetUserPwd($user) {
    // Simulate a successful password reset
    return 1;
}

$user = getUser();
$passwordToCheck = 'password';
$encryptedPassword = encryptPassword($user->loginName, $passwordToCheck, $user->salt);

if ($user->password === $encryptedPassword) {
    $user->password = encryptPassword($user->loginName, 'newPassword', 'newSalt');
    resetUserPwd($user);
}
--EXPECT--
