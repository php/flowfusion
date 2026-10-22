--TEST--
Translated seed (inferredbugs-csharp: BiliBiliToolPro/8)
--FILE--
<?php

class PushPlusApiClient {
    private const HOST = "http://www.pushplus.plus/send";

    private $apiUrl;
    private $token;
    private $topic;

    public function __construct($token, $topic = null) {
        $this->apiUrl = self::HOST;
        $this->token = $token;
        $this->topic = $topic;
    }

    public function pushMessage($message) {
        $data = [
            'token' => $this->token,
            'title' => 'Ray.BiliBiliTool任务日报',
            'content' => $message,
            'topic' => $this->topic,
            'template' => 'markdown'
        ];
        
        $json = json_encode($data);
        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => $json,
            ],
        ];
        
        $context = stream_context_create($options);
        $response = file_get_contents($this->apiUrl, false, $context);
    }
}

$client = new PushPlusApiClient("your_token_here", "your_topic_here");
$client->pushMessage("This is a test message.");
--EXPECT--
