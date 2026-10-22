--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/42)
--FILE--
<?php

class PingUtils {

    public static function getPing($player) {
        $ping = 0;

        if ($player === null) {
            return $ping;
        }

        try {
            $craftPlayer = new ReflectionClass("CraftPlayer"); // Simulating reflection to get class
            $cp = $craftPlayer->newInstanceArgs([$player]); // Simulating casting player
            $handle = $craftPlayer->getMethod("getHandle")->invoke($cp);
            $ping = $handle->ping; // Assuming $handle has a public property ping
        } catch (Exception $e) {
            error_log($e->getMessage()); // Logging exception
        }

        return $ping;
    }

}

$player = null; // Change this to simulate a player or null
$pingResult = PingUtils::getPing($player);
--EXPECT--
