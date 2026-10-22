--TEST--
Translated seed (inferredbugs-java: LWC/2)
--FILE--
<?php

class AccessRight {
    const RIGHT_NOACCESS = -1;
    const RIGHT_PLAYER = 0;
    const RIGHT_ADMIN = 1;
    const GROUP = 0;
    const PLAYER = 1;
    const LIST = 2;
    const TEMPORARY = 3;

    private $name;
    private $protectionId;
    private $rights;
    private $type;

    public function encodeToJSON() {
        return json_encode([
            "protection" => $this->protectionId,
            "type" => $this->type,
            "name" => $this->name,
            "rights" => $this->rights
        ]);
    }

    public static function decodeJSON($node) {
        $right = new self();
        $data = json_decode($node, true);
        $right->setProtectionId((int)$data["protection"]);
        $right->setType((int)$data["type"]);
        $right->setName($data["name"]);
        $right->setRights((int)$data["rights"]);
        return $right;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setProtectionId($protectionId) {
        $this->protectionId = $protectionId;
    }

    public function setRights($rights) {
        $this->rights = $rights;
    }

    public function setType($type) {
        $this->type = $type;
    }
}

$accessRight = new AccessRight();
$accessRight->setProtectionId(1);
$accessRight->setType(AccessRight::PLAYER);
$accessRight->setName("PlayerName");
$accessRight->setRights(AccessRight::RIGHT_PLAYER);

$json = $accessRight->encodeToJSON();
$decodedRight = AccessRight::decodeJSON($json);
--EXPECT--
