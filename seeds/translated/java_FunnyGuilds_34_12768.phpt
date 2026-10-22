--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/34)
--FILE--
<?php

class Guild {
    private $uuid;
    private $name;
    private $members = [];
    private $lives = 0;
    private $changes = false;

    private function __construct($uuid) {
        $this->uuid = $uuid;
        $this->changes = true;
    }

    public static function getOrCreate($name) {
        static $guilds = [];

        foreach ($guilds as $guild) {
            if ($guild->getName() === $name) {
                return $guild;
            }
        }

        $guild = new Guild(uniqid());
        $guild->name = $name;
        $guilds[] = $guild;
        return $guild;
    }

    public function addMember($user) {
        if (in_array($user, $this->members, true)) {
            return;
        }

        $this->members[] = $user;
        $this->updateRank();
        $this->changes();
    }

    public function removeMember($user) {
        if (($key = array_search($user, $this->members, true)) !== false) {
            unset($this->members[$key]);
        }
        $this->updateRank();
        $this->changes();
    }

    public function addLive() {
        $this->lives++;
        $this->changes();
    }

    public function removeLive() {
        $this->lives--;
        $this->changes();
    }

    public function changed() {
        $c = $this->changes;
        if ($c) {
            $this->changes = false;
        }
        return $c;
    }

    public function changes() {
        $this->changes = true;
    }

    public function getName() {
        return $this->name;
    }

    public function getUUID() {
        return $this->uuid;
    }

    private function updateRank() {
        // Simulate rank update logic
    }
}

$guild = Guild::getOrCreate("Test Guild");
$guild->addLive();
$guild->addMember("User1");
$guild->removeMember("User1");
$guild->removeLive();
$guild->changed();

?>
--EXPECT--
