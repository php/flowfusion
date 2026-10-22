--TEST--
Translated seed (inferredbugs-java: blynk-server/35)
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

    public function __construct($to, $priority, $body, $dashId) {
        $this->to = $to;
        $this->priority = $priority;
        $this->body = $body;
        $this->dashId = $dashId;
        $this->title = "Blynk Notification";
    }

    public function toJson() {
        return json_encode([
            'to' => $this->to,
            'priority' => $this->priority,
            'notification' => [
                'title' => $this->title,
                'body' => $this->body,
                'dashId' => $this->dashId,
                'sound' => 'default'
            ]
        ]);
    }
}

class AndroidGCMMessage {
    private $to;
    private $priority;
    private $message;
    private $dashId;

    public function __construct($to, $priority, $message, $dashId) {
        $this->to = $to;
        $this->priority = $priority;
        $this->message = $message;
        $this->dashId = $dashId;
    }

    public function toJson() {
        return json_encode([
            'to' => $this->to,
            'priority' => $this->priority,
            'data' => [
                'message' => $this->message,
                'dashId' => $this->dashId
            ]
        ]);
    }
}

$iosMessage = new IOSGCMMessage("to", Priority::normal, "yo!!!", 1);
$androidMessage = new AndroidGCMMessage("", Priority::normal, "yo!!!", 1);

$iosJson = $iosMessage->toJson();
$androidJson = $androidMessage->toJson();
--EXPECT--
