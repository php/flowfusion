--TEST--
Translated seed (inferredbugs-java: RuoYi/9)
--FILE--
<?php

class ShiroUtils
{
    private static $session = [];

    public static function getSubject()
    {
        return self::$session;
    }

    public static function getSession()
    {
        return self::$session;
    }

    public static function logout()
    {
        self::$session = [];
    }

    public static function getUser()
    {
        return isset(self::$session['sysUser']) ? self::$session['sysUser'] : null;
    }

    public static function setSysUser($user)
    {
        self::$session['sysUser'] = $user;
    }

    public static function getUserId()
    {
        $user = self::getUser();
        return $user ? (int)$user['userId'] : null;
    }

    public static function getLoginName()
    {
        $user = self::getUser();
        return $user ? $user['loginName'] : null;
    }

    public static function getIp()
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public static function getSessionId()
    {
        return session_id();
    }

    public static function randomSalt()
    {
        return bin2hex(random_bytes(3));
    }
}

session_start();
$user = ['userId' => 123, 'loginName' => 'testUser'];
ShiroUtils::setSysUser($user);
echo ShiroUtils::getUserId() . "\n";
echo ShiroUtils::getLoginName() . "\n";
echo ShiroUtils::getIp() . "\n";
echo ShiroUtils::getSessionId() . "\n";
echo ShiroUtils::randomSalt() . "\n";

ShiroUtils::logout();
?>
--EXPECT--
