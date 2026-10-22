--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/31)
--FILE--
<?php

class Guild {
    private $name;
    private $tag;
    private $banned;

    public function __construct($name, $tag, $banned) {
        $this->name = $name;
        $this->tag = $tag;
        $this->banned = $banned;
    }

    public function isBanned() {
        return $this->banned;
    }

    public function getName() {
        return $this->name;
    }

    public function getTag() {
        return $this->tag;
    }

    public function unban() {
        $this->banned = false;
    }
}

class GuildUtils {
    private static $guilds = [];

    public static function addGuild($guild) {
        self::$guilds[$guild->getTag()] = $guild;
    }

    public static function tagExists($tag) {
        return isset(self::$guilds[$tag]);
    }

    public static function byTag($tag) {
        return self::$guilds[$tag];
    }
}

class Messages {
    public $generalNoTagGiven = "No tag given.";
    public $generalNoGuildFound = "No guild found.";
    public $adminGuildNotBanned = "Guild is not banned.";
    public $adminGuildUnban = "Guild {GUILD} has been unbanned.";
    public $broadcastUnban = "Guild {GUILD} with tag {TAG} has been unbanned.";
}

function execute($args) {
    $messages = new Messages();

    if (count($args) < 1) {
        echo $messages->generalNoTagGiven . PHP_EOL;
        return;
    }

    if (!GuildUtils::tagExists($args[0])) {
        echo $messages->generalNoGuildFound . PHP_EOL;
        return;
    }

    $guild = GuildUtils::byTag($args[0]);
    if (!$guild->isBanned()) {
        echo $messages->adminGuildNotBanned . PHP_EOL;
        return;
    }

    $guild->unban();
    echo str_replace("{GUILD}", $guild->getName(), $messages->adminGuildUnban) . PHP_EOL;
    echo str_replace(["{GUILD}", "{TAG}"], [$guild->getName(), $guild->getTag()], $messages->broadcastUnban) . PHP_EOL;
}

$guild1 = new Guild("Knights", "KNT", true);
GuildUtils::addGuild($guild1);

execute($argv);
--EXPECT--
