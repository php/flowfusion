--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/5)
--FILE--
<?php

class Guild {}
class Location {
    public $world;
    public $x;
    public $y;
    public $z;

    public function __construct($world, $x, $y, $z) {
        $this->world = $world;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
}
class Region {
    public static function get($region) {
        return new self();
    }
    public function getCenter() {
        return new Location("world", 0, 0, 0);
    }
}
class PacketSender {
    public static function sendPacket($players, $packet) {}
}
class Player {}

class EntityUtil {
    private static $entitiesMap = [];
    private static $ids = [];

    public static function spawn(Guild $guild) {
        if (!array_key_exists(spl_object_id($guild), self::$entitiesMap)) {
            $center = Region::get($guild->getRegion())->getCenter();
            if ($center === null) {
                return;
            }
            $loc = new Location($center->world, $center->x + 0.5, $center->y - 1.0, $center->z + 0.5);
            $id = self::spawnPacket($loc);
            self::$entitiesMap[spl_object_id($guild)] = $id;
        }
        $packet = self::$ids[self::$entitiesMap[spl_object_id($guild)]];
        PacketSender::sendPacket([], $packet);
    }

    private static function spawnPacket(Location $loc) {
        $id = count(self::$ids) + 1; // Simple ID generation simulation
        self::$ids[$id] = "SpawnPacketForID" . $id; // Simulating packet creation
        return $id;
    }

    public static function despawn(Guild $guild) {
        if (array_key_exists(spl_object_id($guild), self::$entitiesMap)) {
            $id = self::$entitiesMap[spl_object_id($guild)];
            unset(self::$ids[$id]);
            unset(self::$entitiesMap[spl_object_id($guild)]);
            $packet = "DespawnPacketForID" . $id; // Simulating packet creation
            PacketSender::sendPacket([], $packet);
        }
    }
}

$guild = new Guild();
EntityUtil::spawn($guild);
EntityUtil::despawn($guild);
--EXPECT--
