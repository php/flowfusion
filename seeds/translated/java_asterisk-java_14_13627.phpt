--TEST--
Translated seed (inferredbugs-java: asterisk-java/14)
--FILE--
<?php

class AsteriskQueueMember {
    private $location;
    private $state;
    private $paused;
    private $penalty;
    private $membership;

    public function __construct($location, $state, $paused, $penalty, $membership) {
        $this->location = $location;
        $this->state = $state;
        $this->paused = $paused;
        $this->penalty = $penalty;
        $this->membership = $membership;
    }

    public function setPenalty($penalty) {
        if ($penalty < 0) {
            throw new InvalidArgumentException("Penalty must not be negative");
        }
        $this->penalty = $penalty;
    }

    public function isStatic() {
        return $this->membership === "static";
    }

    public function isDynamic() {
        return $this->membership === "dynamic";
    }

    public function toString() {
        return "AsteriskQueueMember[location='$this->location', state='$this->state', paused='$this->paused', membership='$this->membership', penalty='$this->penalty']";
    }
}

$member = new AsteriskQueueMember("Location1", "active", false, 0, "dynamic");
$member->setPenalty(10);
$member->setPenalty(-5);
echo $member->toString();
--EXPECT--
