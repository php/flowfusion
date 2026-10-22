--TEST--
Translated seed (inferredbugs-java: asterisk-java/9)
--FILE--
<?php

class AsteriskAgentImpl {
    public $name;
    public $agentId;
    public $status;

    function __construct($server, $name, $agentId, $status) {
        if ($server === null || $name === null || $agentId === null) {
            throw new InvalidArgumentException("Parameters passed to AsteriskAgentImpl() must not be null.");
        }
        $this->name = $name;
        $this->agentId = $agentId;
        $this->status = $status;
    }

    function getName() {
        return $this->name;
    }

    function getAgentId() {
        return $this->agentId;
    }

    function getStatus() {
        return $this->status;
    }

    function updateStatus($agentState) {
        $oldStatus = $this->status;
        $this->status = $agentState;
    }

    function __toString() {
        return "AsteriskAgent[agentId='{$this->getAgentId()}', name='{$this->getName()}', state={$this->getStatus()}, systemHashcode=" . spl_object_hash($this) . "]";
    }
}

$agent = new AsteriskAgentImpl('server', 'Agent007', 'ID007', 'ACTIVE');
$agent->updateStatus('INACTIVE');
echo $agent;
--EXPECT--
