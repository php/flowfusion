--TEST--
Translated seed (inferredbugs-csharp: AtomEventStore/5)
--FILE--
<?php

class UserCreated {
    public $UserId;
    public $UserName;
    public $Password;
    public $Email;
}

class EmailVerified {
    public $UserId;
    public $Email;
}

class EmailChanged {
    public $UserId;
    public $NewEmail;
}

class AtomEventsInMemory {
    private $storage = [];

    public function add($event) {
        $this->storage[] = $event;
    }

    public function getAll() {
        return $this->storage;
    }
}

class AtomEventObserver {
    private $eventStreamId;
    private $pageSize;
    private $storage;

    public function __construct($eventStreamId, $pageSize, $storage) {
        $this->eventStreamId = $eventStreamId;
        $this->pageSize = $pageSize;
        $this->storage = $storage;
    }

    public function onNext($event) {
        $this->storage->add($event);
    }
}

function main() {
    $eventStreamId = "A0E50259-7345-48F9-84B4-BEEB5CEC662C";
    $storage = new AtomEventsInMemory();
    $pageSize = 25;

    $obs = new AtomEventObserver($eventStreamId, $pageSize, $storage);

    $userCreated = new UserCreated();
    $userCreated->UserId = $eventStreamId;
    $userCreated->UserName = "ploeh";
    $userCreated->Password = "12345";
    $userCreated->Email = "ploeh@fnaah.com";

    $obs->onNext($userCreated);

    if (count($storage->getAll()) > 0) {
        echo "Event storage is not empty.\n";
    }
}

main();
--EXPECT--
