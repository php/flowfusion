--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/29)
--FILE--
<?php

class Messages {
    public static function getInstance() {
        return new self();
    }

    public $adminStopSpy = "Spy mode disabled.";
    public $adminStartSpy = "Spy mode enabled.";
}

class User {
    private static $users = [];
    private $spy = false;

    public static function get($player) {
        if (!isset(self::$users[$player])) {
            self::$users[$player] = new self();
        }
        return self::$users[$player];
    }

    public function isSpy() {
        return $this->spy;
    }

    public function setSpy($value) {
        $this->spy = $value;
    }
}

function execute($sender, $args) {
    $messages = Messages::getInstance();
    $user = User::get($sender);

    if ($user->isSpy()) {
        $user->setSpy(false);
        echo $messages->adminStopSpy . PHP_EOL;
    } else {
        $user->setSpy(true);
        echo $messages->adminStartSpy . PHP_EOL;
    }
}

$player = "Player1"; // Simulating a player
$args = []; // No specific args needed for this test case
execute($player, $args);

?>
--EXPECT--
