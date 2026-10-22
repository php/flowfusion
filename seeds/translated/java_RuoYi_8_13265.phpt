--TEST--
Translated seed (inferredbugs-java: RuoYi/8)
--FILE--
<?php

class User {
    public $userId;
    public $loginName;
}

class Session {
    private $attributes = [];

    public function setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function getAttribute($key) {
        return $this->attributes[$key] ?? null;
    }

    public function getHost() {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function getId() {
        return session_id();
    }
}

class Subject {
    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function getSession() {
        return $this->session;
    }

    public function logout() {
        session_destroy();
    }
}

function getSubject() {
    return new Subject();
}

function randomSalt() {
    return bin2hex(random_bytes(3));
}

$subject = getSubject();
$user = new User();
$user->userId = 123;
$user->loginName = "testUser";

$subject->getSession()->setAttribute("sysUser", $user);
echo $subject->getSession()->getHost() . "\n";
echo $subject->getSession()->getId() . "\n";
echo randomSalt() . "\n";
--EXPECT--
