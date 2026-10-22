--TEST--
Translated seed (inferredbugs-java: Spigot-API/4)
--FILE--
<?php

class Player {
    public $name;
    public $world;

    function __construct($name, $world) {
        $this->name = $name;
        $this->world = $world;
    }

    function teleport($location) {
        // Simulate teleporting the player
        echo "Teleported " . $this->name . " to " . $location->x . "," . $location->y . "," . $location->z . "\n";
    }
}

class Location {
    public $world;
    public $x;
    public $y;
    public $z;

    function __construct($world, $x, $y, $z) {
        $this->world = $world;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
}

function getInteger($value, $min, $max) {
    $intValue = intval($value);
    if ($intValue < $min || $intValue > $max) {
        return null;
    }
    return $intValue;
}

$world = "world";
$player = new Player("Steve", $world);

$args = ["Steve", "30000001", "0", "30000001"]; // simulate input args

if (count($args) < 1 || count($args) > 4) {
    echo "Usage: /tp [player] <target>\n/tp [player] <x> <y> <z>\n";
    exit;
}

if (count($args) == 1 || count($args) == 3) {
    // Simulating sender being a player
} else {
    $target = new Player($args[0], $world); // simulate getting target player
}

if (!isset($target)) {
    echo "Player not found: " . $args[0] . "\n";
}

if (count($args) < 3) {
    // Simulate finding target player
    $target = new Player($args[count($args) - 1], $world); // simulate getting target player
    if (!isset($target)) {
        echo "Can't find user " . $args[count($args) - 1] . ". No tp.\n";
    }
    $player->teleport($target);
} else if ($player->world != null) {
    $x = getInteger($args[count($args) - 3], -30000000, 30000000);
    $y = getInteger($args[count($args) - 2], 0, 256);
    $z = getInteger($args[count($args) - 1], -30000000, 30000000);

    $location = new Location($player->world, $x, $y, $z);
    $player->teleport($location);
}
--EXPECT--
