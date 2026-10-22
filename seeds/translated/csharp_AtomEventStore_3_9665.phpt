--TEST--
Translated seed (inferredbugs-csharp: AtomEventStore/3)
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
    private $events = [];

    public function addEvent($event) {
        $this->events[] = $event;
    }

    public function getAllEvents() {
        return $this->events;
    }
}

class AtomEventObserver {
    private $eventStreamId;
    private $storage;

    public function __construct($eventStreamId, $storage) {
        $this->eventStreamId = $eventStreamId;
        $this->storage = $storage;
    }

    public function OnNext($event) {
        $this->storage->addEvent($event);
    }
}

function main() {
    $eventStreamId = 'A0E50259-7345-48F9-84B4-BEEB5CEC662C';
    $storage = new AtomEventsInMemory();
    $obs = new AtomEventObserver($eventStreamId, $storage);

    $userCreated = new UserCreated();
    $userCreated->UserId = $eventStreamId;
    $userCreated->UserName = "ploeh";
    $userCreated->Password = "12345";
    $userCreated->Email = "ploeh@fnaah.com";

    $obs->OnNext($userCreated);

    if (empty($storage->getAllEvents())) {
        exit(1);
    }

    $events = $storage->getAllEvents();
    $firstEvent = $events[0];

    if (!$firstEvent instanceof UserCreated) {
        exit(1);
    }
}

main();
--EXPECT--
