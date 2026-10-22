--TEST--
Translated seed (inferredbugs-java: blynk-server/2)
--FILE--
<?php

class LoadProfileMessage {
    public $id;
    public $command;

    public function __construct($id, $command) {
        $this->id = $id;
        $this->command = $command;
    }
}

class User {
    private $profile;

    public function __construct($profile) {
        $this->profile = $profile;
    }

    public function getUserProfile() {
        return $this->profile;
    }
}

class Session {
    private static $users = [];

    public static function addUser($channel, $user) {
        self::$users[$channel] = $user;
    }

    public static function findUserByChannel($channel) {
        return self::$users[$channel] ?? null;
    }
}

function produce($id, $command, $body) {
    return json_encode(['id' => $id, 'command' => $command, 'body' => $body]);
}

function main() {
    $userChannel = 'channel1';
    Session::addUser($userChannel, new User(null));

    $message = new LoadProfileMessage(1, 'loadProfile');
    $authUser = Session::findUserByChannel($userChannel);

    $body = $authUser->getUserProfile() === null ? '{}' : json_encode($authUser->getUserProfile());
    echo produce($message->id, $message->command, $body);
}

main();
--EXPECT--
