--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/21)
--FILE--
<?php

class AxcBan {

    public function execute($sender, $args) {
        $messages = Messages::getInstance();

        if (count($args) < 1) {
            $sender->sendMessage($messages->generalNoTagGiven);
            return;
        } else if (count($args) < 2) {
            $sender->sendMessage($messages->adminNoBanTimeGiven);
            return;
        } else if (count($args) < 3) {
            $sender->sendMessage($messages->adminNoReasonGiven);
            return;
        }

        if (!GuildUtils::tagExists($args[0])) {
            $sender->sendMessage($messages->generalNoGuildFound);
            return;
        } 
        
        $guild = GuildUtils::byTag($args[0]);
        if ($guild->isBanned()) {
            $sender->sendMessage($messages->adminGuildBanned);
            return;
        }

        $time = Parser::parseTime($args[1]);
        if ($time < 1) {
            $sender->sendMessage($messages->adminTimeError);
            return;
        }

        $reason = implode(" ", array_slice($args, 2));

        BanUtils::ban($guild, $time, $reason);
        $sender->sendMessage(str_replace(["{GUILD}", "{TIME}"], [$guild->getName(), $args[1]], $messages->adminGuildBan));
        echo str_replace(
            ["{GUILD}", "{TAG}", "{REASON}", "{TIME}"],
            [$guild->getName(), $guild->getTag(), StringUtils::colored($reason), $args[1]],
            Messages::getInstance()->broadcastBan
        );
    }
}

// Example usage
$sender = new class {
    public function sendMessage($message) {
        echo $message . PHP_EOL;
    }
};

$banCommand = new AxcBan();
$banCommand->execute($sender, ['exampleGuild', '3600', 'Misbehavior']);
--EXPECT--
