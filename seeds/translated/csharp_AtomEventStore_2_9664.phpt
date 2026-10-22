--TEST--
Translated seed (inferredbugs-csharp: AtomEventStore/2)
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

class AtomEventObserver {
    private $eventStreamId;
    private $storage;
    private $events = [];

    public function __construct($eventStreamId, $storage) {
        $this->eventStreamId = $eventStreamId;
        $this->storage = $storage;
    }

    public function onNext($event) {
        $this->events[] = $event;
        $this->storage[] = $event;
    }
}

function main() {
    $eventStreamId = "A0E50259-7345-48F9-84B4-BEEB5CEC662C";
    $storage = [];
    
    $observer = new AtomEventObserver($eventStreamId, $storage);
    
    $userCreated = new UserCreated();
    $userCreated->UserId = $eventStreamId;
    $userCreated->UserName = "ploeh";
    $userCreated->Password = "12345";
    $userCreated->Email = "ploeh@fnaah.com";
    
    $observer->onNext($userCreated);
    
    $emailVerified = new EmailVerified();
    $emailVerified->UserId = $eventStreamId;
    $emailVerified->Email = "ploeh@fnaah.com";
    
    $observer->onNext($emailVerified);
    
    $emailChanged = new EmailChanged();
    $emailChanged->UserId = $eventStreamId;
    $emailChanged->NewEmail = "fnaah@ploeh.com";
    
    $observer->onNext($emailChanged);
    
    if (!empty($storage)) {
        echo "Events stored successfully.";
    }
}

main();
--EXPECT--
