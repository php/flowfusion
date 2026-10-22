--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/25)
--FILE--
<?php

class AxcLives {

    public function execute($args) {
        $messages = [
            'generalNoTagGiven' => 'No tag given.',
            'adminNoLivesGiven' => 'No lives given.',
            'generalNoGuildFound' => 'No guild found.',
            'adminErrorInNumber' => 'Error in number: {ERROR}.',
            'adminLivesChanged' => 'Lives for guild {GUILD} changed to {LIVES}.',
        ];

        if (count($args) < 1) {
            echo $messages['generalNoTagGiven'];
            return;
        }

        if (count($args) < 2) {
            echo $messages['adminNoLivesGiven'];
            return;
        }

        if (!GuildUtils::tagExists($args[0])) {
            echo $messages['generalNoGuildFound'];
            return;
        }

        $guild = GuildUtils::byTag($args[0]);

        $lives = null;
        try {
            $lives = intval($args[1]);
            if ($lives === 0 && $args[1] !== "0") {
                throw new Exception("Invalid number format");
            }
        } catch (Exception $e) {
            echo str_replace('{ERROR}', $args[1], $messages['adminErrorInNumber']);
            return;
        }

        $guild->setLives($lives);
        echo str_replace(['{GUILD}', '{LIVES}'], [$guild->getTag(), strval($lives)], $messages['adminLivesChanged']);
    }
}

class GuildUtils {
    public static function tagExists($tag) {
        // Simulate checking for a guild tag existence
        return $tag === "validTag";
    }

    public static function byTag($tag) {
        // Simulate returning a guild object
        return new Guild($tag);
    }
}

class Guild {
    private $tag;
    private $lives;

    public function __construct($tag) {
        $this->tag = $tag;
        $this->lives = 0; // default lives
    }

    public function setLives($lives) {
        $this->lives = $lives;
    }

    public function getTag() {
        return $this->tag;
    }
}

$axcLives = new AxcLives();
$axcLives->execute($argv);
--EXPECT--
