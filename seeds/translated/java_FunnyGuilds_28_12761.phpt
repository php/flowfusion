--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/28)
--FILE--
<?php

class User {
    private static $users = [];
    private $isSpy = false;

    public static function get($sender) {
        if (!isset(self::$users[$sender])) {
            self::$users[$sender] = new self();
        }
        return self::$users[$sender];
    }

    public function isSpy() {
        return $this->isSpy;
    }

    public function setSpy($value) {
        $this->isSpy = $value;
    }
}

class Messages {
    private static $instance;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public $adminStartSpy = "You are now spying.";
    public $adminStopSpy = "You have stopped spying.";
}

function execute($sender) {
    $messages = Messages::getInstance();
    $user = User::get($sender);

    if ($user->isSpy()) {
        $user->setSpy(false);
        echo $messages->adminStopSpy;
    } else {
        $user->setSpy(true);
        echo $messages->adminStartSpy;
    }
}

$sender = "player1"; // Simulating a player command
execute($sender);
--EXPECT--
