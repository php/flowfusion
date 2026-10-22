--TEST--
Translated seed (inferredbugs-java: LWC/9)
--FILE--
<?php

class Protection {
    public $type;
    public $owner;
    public $access = [];

    public function __construct($type, $owner) {
        $this->type = $type;
        $this->owner = $owner;
    }
    
    public function isOwner($player) {
        return $this->owner === $player;
    }

    public function getAccess($player) {
        return isset($this->access[$player]) ? $this->access[$player] : 'NONE';
    }

    public function setAccess($player, $level) {
        $this->access[$player] = $level;
    }
}

class Player {
    public $name;
    public $permissions = [];

    public function __construct($name) {
        $this->name = $name;
    }

    public function hasPermission($permission) {
        return in_array($permission, $this->permissions);
    }

    public function givePermission($permission) {
        $this->permissions[] = $permission;
    }
}

function canAccessProtection($player, $protection) {
    if ($protection->type === 'PUBLIC' || $protection->isOwner($player->name)) {
        return true;
    }
    return $protection->getAccess($player->name) === 'ADMIN';
}

$player1 = new Player("Alice");
$player2 = new Player("Bob");

$protection = new Protection('PRIVATE', 'Alice');
$protection->setAccess('Bob', 'ADMIN');

$player1->givePermission('access.private');
$player2->givePermission('access.private');

var_dump(canAccessProtection($player1, $protection)); // true
var_dump(canAccessProtection($player2, $protection)); // true
--EXPECT--
