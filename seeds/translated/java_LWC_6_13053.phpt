--TEST--
Translated seed (inferredbugs-java: LWC/6)
--FILE--
<?php

class Permission {

    const NONE = 0;
    const PLAYER = 1;
    const ADMIN = 2;

    const GROUP = 0;
    const PLAYER_TYPE = 1;
    const LIST = 2;
    const TOWN = 3;
    const ITEM = 4;

    private $name;
    private $type;
    private $access = self::PLAYER;
    private $isVolatile = false;

    public function __construct($name = null, $type = null, $access = null) {
        $this->name = $name;
        if ($type !== null) $this->type = $type;
        if ($access !== null) $this->access = $access;
    }

    public function encodeToJSON() {
        return json_encode([
            "name" => $this->name,
            "type" => $this->type,
            "rights" => $this->access
        ]);
    }

    public static function decodeJSON($json) {
        $node = json_decode($json, true);
        return new Permission(
            $node["name"],
            $node["type"],
            $node["rights"]
        );
    }

    public function __toString() {
        return $this->name . " (". $this->type .") " . ($this->access === self::ADMIN ? "(ADMIN)" : "");
    }
}

$permission = new Permission("ExampleName", Permission::PLAYER_TYPE, Permission::ADMIN);
$json = $permission->encodeToJSON();
$decodedPermission = Permission::decodeJSON($json);
echo $decodedPermission;
--EXPECT--
