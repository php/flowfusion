--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/4)
--FILE--
<?php

class EntityUtil {
    private static $entitiesMap = [];
    private static $ids = [];

    public static function spawn($guild) {
        if (!isset(self::$entitiesMap[$guild])) {
            $center = Region::get($guild->getRegion())->getCenter();
            if ($center === null) {
                return;
            }
            $loc = new Location($center->getWorld(), $center->getX() + 0.5, $center->getY() - 1.0, $center->getZ() + 0.5);
            $id = self::spawnPacket($loc);
            self::$entitiesMap[$guild] = $id;
        }
        $o = self::$ids[self::$entitiesMap[$guild]];
        PacketSender::sendPacket(Bukkit::getOnlinePlayers(), $o);
    }

    private static function spawnPacket($loc) {
        $id = rand(1, PHP_INT_MAX); // Simulating entity id generation
        self::$ids[$id] = "SpawnPacket for " . $id; // Mock packet for demonstration
        return $id;
    }

    public static function despawn($guild) {
        if (isset(self::$entitiesMap[$guild])) {
            $id = self::$entitiesMap[$guild];
            unset(self::$ids[$id]);
            unset(self::$entitiesMap[$guild]);
            $o = self::despawnPacket($id);
            PacketSender::sendPacket(Bukkit::getOnlinePlayers(), $o);
        }
    }

    private static function despawnPacket($id) {
        return "DespawnPacket for " . $id; // Mock packet for demonstration
    }

    public static function runDemo() {
        // Create a mock guild and region
        $guild = new Guild();
        $region = new Region();
        $region->setCenter(new Location("world", 0, 64, 0)); // Mock location
        $guild->setRegion($region);

        // Spawn and despawn entities to trigger the bug pattern
        self::spawn($guild);
        self::despawn($guild);
    }
}

EntityUtil::runDemo();
--EXPECT--
