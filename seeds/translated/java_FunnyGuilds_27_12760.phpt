--TEST--
Translated seed (inferredbugs-java: FunnyGuilds/27)
--FILE--
<?php

class AxcName
{
    public function execute($sender, $args)
    {
        $messages = Messages::getInstance();

        if (count($args) < 1) {
            $sender->sendMessage($messages->generalNoTagGiven);
            return;
        } elseif (count($args) < 2) {
            $sender->sendMessage($messages->adminNoNewNameGiven);
            return;
        }

        if (!GuildUtils::tagExists($args[0])) {
            $sender->sendMessage($messages->generalNoGuildFound);
            return;
        }

        if (GuildUtils::nameExists($args[1])) {
            $sender->sendMessage($messages->createNameExists);
            return;
        }

        $guild = GuildUtils::byTag($args[0]);
        $region = RegionUtils::get($guild->getRegion());

        $dataType = Settings::getConfig()->dataType;
        Manager::getInstance()->stop();

        if ($dataType->flat) {
            unlink(Flat::getGuildFile($guild));
            unlink(Flat::getRegionFile($region));
        }

        if ($dataType->mysql) {
            $databaseGuild = new DatabaseGuild($guild);
            $databaseRegion = new DatabaseRegion($region);
            $databaseGuild->delete();
            $databaseRegion->delete();
        }

        $guild->setName($args[1]);
        $region->setName($args[1]);

        Manager::getInstance()->start();
        $sender->sendMessage(str_replace("{GUILD}", $guild->getName(), $messages->adminNameChanged));
    }
}

$sender = new CommandSender(); // Instantiate or mock CommandSender
$args = ['guildTag', 'newName']; // Example arguments
$axcName = new AxcName();
$axcName->execute($sender, $args);
--EXPECT--
