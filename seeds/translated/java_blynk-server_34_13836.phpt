--TEST--
Translated seed (inferredbugs-java: blynk-server/34)
--FILE--
<?php

class Priority {
    const normal = 'normal';
}

class IOSGCMMessage {
    private $to;
    private $priority;
    private $title;
    private $body;
    private $dashId;
    private $sound;
    
    public function __construct($to, $priority, $body, $dashId) {
        $this->to = $to;
        $this->priority = $priority;
        $this->title = 'Blynk Notification';
        $this->body = $body;
        $this->dashId = $dashId;
        $this->sound = 'default';
    }
    
    public function toJson() {
        return json_encode([
            'to' => $this->to,
            'priority' => $this->priority,
            'notification' => [
                'title' => $this->title,
                'body' => $this->body,
                'dashId' => $this->dashId,
                'sound' => $this->sound
            ]
        ]);
    }
}

class AndroidGCMMessage {
    private $to;
    private $priority;
    private $data;
    
    public function __construct($to, $priority, $body, $dashId) {
        $this->to = $to;
        $this->priority = $priority;
        $this->data = ['message' => $body, 'dashId' => $dashId];
    }
    
    public function toJson() {
        return json_encode([
            'to' => $this->to,
            'priority' => $this->priority,
            'data' => $this->data
        ]);
    }
}

$androidMessage = new AndroidGCMMessage("to", Priority::normal, "yo!!!", 1);
$iosMessage = new IOSGCMMessage("to", Priority::normal, "yo!!!", 1);
$androidJson = $androidMessage->toJson();
$iosJson = $iosMessage->toJson();

echo $androidJson . PHP_EOL;
echo $iosJson . PHP_EOL;
--EXPECT--
