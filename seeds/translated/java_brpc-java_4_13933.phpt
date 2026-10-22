--TEST--
Translated seed (inferredbugs-java: brpc-java/4)
--FILE--
<?php

class Channel {
    // Dummy class to simulate Channel behavior
}

class ChannelManager {
    private static $instance;
    private $channelMap = [];
    private $channelSet = [];
    private $index = 0;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new ChannelManager();
        }
        return self::$instance;
    }

    private function __construct() {}

    public function putChannel($clientName, $channel) {
        if (!in_array($channel, $this->channelSet)) {
            if (!isset($this->channelMap[$clientName])) {
                $this->channelMap[$clientName] = [];
            }
            $this->channelMap[$clientName][] = $channel;
            $this->channelSet[] = $channel;
        }
    }

    public function getChannel($clientName) {
        if (!isset($this->channelMap[$clientName]) || count($this->channelMap[$clientName]) === 0) {
            return null;
        }
        $id = $this->index++ % count($this->channelMap[$clientName]);
        return $this->channelMap[$clientName][$id];
    }

    public function removeChannel($channel) {
        foreach ($this->channelMap as $key => &$list) {
            if (($index = array_search($channel, $list)) !== false) {
                unset($list[$index]);
                $this->channelSet = array_filter($this->channelSet, function($c) use ($channel) {
                    return $c !== $channel;
                });
            }
        }
    }
}

$manager = ChannelManager::getInstance();
$channel1 = new Channel();
$channel2 = new Channel();
$manager->putChannel("client1", $channel1);
$manager->putChannel("client1", $channel2);
$retrievedChannel = $manager->getChannel("client1");
$manager->removeChannel($retrievedChannel);
--EXPECT--
