--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/39)
--FILE--
<?php

class WorldGuardHook {
    
    private static $worldGuard;
    private static $noPointsFlag;

    public static function initWorldGuard() {
        self::$worldGuard = new stdClass(); // Simulated WorldGuardPlugin instance
        self::$noPointsFlag = ['name' => 'fg-no-points', 'default' => false];
    }

    public static function isInNonPointsRegion($location) {
        if (!self::isInRegion($location)) {
            return false;
        }
        
        foreach (self::getRegionSet($location) as $region) {
            if (isset($region['flags'][self::$noPointsFlag['name']]) && $region['flags'][self::$noPointsFlag['name']] === true) {
                return true;
            }
        }

        return false;
    }

    public static function isInRegion($location) {
        $regionSet = self::getRegionSet($location);
        return $regionSet !== null && count($regionSet) !== 0;
    }
    
    public static function getRegionSet($location) {
        if ($location === null || self::$worldGuard === null) {
            return null;
        }
        
        // Simulated region manager and regions
        return [
            ['id' => 'region1', 'flags' => ['fg-no-points' => true]],
            ['id' => 'region2', 'flags' => []]
        ];
    }

    public static function getRegionNames($location) {
        if (!self::isInRegion($location)) {
            return null;
        }
        
        $regionNames = [];
        foreach (self::getRegionSet($location) as $region) {
            $regionNames[] = $region['id'];
        }
        
        return $regionNames;
    }
}

WorldGuardHook::initWorldGuard();
$location = new stdClass(); // Simulated location
$nonPointsRegion = WorldGuardHook::isInNonPointsRegion($location);
$regionNames = WorldGuardHook::getRegionNames($location);
--EXPECT--
