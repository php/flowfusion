--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/12)
--FILE--
<?php

class AmqpsIotHubConnection {
    private $state = 'CLOSED';
    private $linkCredit = -1;
    private $nextTag = 0;
    private $sendEndpoint;
    private $receiveEndpoint;
  
    private $senders = [];
    private $receivers = [];
  
    public function __construct($deviceId, $hubName) {
        $this->sendEndpoint = sprintf("/devices/%s/messages/events", $deviceId);
        $this->receiveEndpoint = sprintf("/devices/%s/messages/devicebound", $deviceId);
    }

    public function open() {
        if ($this->state === 'CLOSED') {
            $this->state = 'OPEN';
            // Simulate sending a message
            $this->sendMessage("Test message");
        }
    }

    public function close() {
        if ($this->state === 'OPEN') {
            $this->state = 'CLOSED';
        }
    }

    public function sendMessage($message) {
        if ($this->state === 'CLOSED' || $this->linkCredit <= 0) {
            return -1;
        }
        
        $msgData = str_pad($message, 1024, " ");
        $length = strlen($msgData);
        
        while (true) {
            try {
                // Simulate potential overflow by trying to encode a large message
                if ($length > 1024) {
                    throw new Exception("Buffer Overflow");
                }
                break;
            } catch (Exception $e) {
                $msgData = str_pad($msgData, strlen($msgData) * 2, " ");
                $length = strlen($msgData);
            }
        }
        
        // Simulating the delivery tag and sending the message
        $tag = $this->nextTag++;
        // For simplicity, we'll just print the message
        echo "Message sent with tag: $tag, content: $msgData\n";
        
        return $tag;
    }
}

$connection = new AmqpsIotHubConnection('deviceId', 'hubName');
$connection->open();
$connection->close();
--EXPECT--
