--TEST--
Translated seed (inferredbugs-java: FastLogin/1)
--FILE--
<?php

class FastLoginBukkit {
    private $loginSessions = [];

    public function getLoginSessions() {
        return $this->loginSessions;
    }

    public function addLoginSession($id, $session) {
        $this->loginSessions[$id] = $session;
    }
}

class Player {
    private $address;

    public function __construct($ip, $port) {
        $this->address = ['address' => $ip, 'port' => $port];
    }

    public function getAddress() {
        return $this->address;
    }
}

class BukkitLoginSession {
    private $verified;

    public function __construct($verified) {
        $this->verified = $verified;
    }

    public function isVerified() {
        return $this->verified;
    }
}

class RestoreSessionEvent {
    private $player;
    private $cancelled = false;

    public function __construct($player) {
        $this->player = $player;
    }

    public function getPlayer() {
        return $this->player;
    }

    public function setCancelled($cancelled) {
        $this->cancelled = $cancelled;
    }

    public function isCancelled() {
        return $this->cancelled;
    }
}

class AuthMeHook {
    private $plugin;

    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    public function onSessionRestore($restoreSessionEvent) {
        $player = $restoreSessionEvent->getPlayer();

        $id = '/' . $player->getAddress()['address'] . ':' . $player->getAddress()['port'];
        $session = $this->plugin->getLoginSessions()[$id] ?? null;
        if ($session !== null && $session->isVerified()) {
            $restoreSessionEvent->setCancelled(true);
        }
    }
}

$plugin = new FastLoginBukkit();
$player = new Player('192.168.1.1', 8080);
$session = new BukkitLoginSession(true);
$plugin->addLoginSession('/192.168.1.1:8080', $session);
$event = new RestoreSessionEvent($player);
$authMeHook = new AuthMeHook($plugin);
$authMeHook->onSessionRestore($event);
--EXPECT--
