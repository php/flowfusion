--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/32)
--FILE--
<?php

class AxcValidity {

    public function execute($sender, $args) {
        $messages = Messages::getInstance();

        if (count($args) < 1) {
            $sender->sendMessage($messages->generalNoTagGiven);
            return;
        } elseif (count($args) < 2) {
            $sender->sendMessage($messages->adminNoValidityTimeGiven);
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

        $validity = $guild->getValidity();
        if ($validity == 0) {
            $validity = time() * 1000; // Current time in milliseconds
        }

        $validity += $time; // Edge case: Check for integer overflow
        $guild->setValidity($validity);

        $date = date('Y-m-d H:i:s', $validity / 1000); // Convert milliseconds to seconds for formatting
        $msg = str_replace(['{GUILD}', '{VALIDITY}'], [$guild->getName(), $date], $messages->adminNewValidity);
        $sender->sendMessage($msg);
    }
}

$axcValidity = new AxcValidity();
$axcValidity->execute(new CommandSender(), ["exampleTag", "3600000"]); // Example test input for execution
--EXPECT--
