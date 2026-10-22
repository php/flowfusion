--TEST--
Translated seed (inferredbugs-csharp: BiliBiliToolPro/9)
--FILE--
<?php

class PushPlusApiClient {
    private const HOST = "http://www.pushplus.plus/send";
    private $apiUrl;
    private $token;
    private $topic;

    public function __construct(string $token, ?string $topic = null) {
        $this->apiUrl = self::HOST;
        $this->token = $token;
        $this->topic = $topic;
    }

    public function pushMessage(string $message) {
        $json = json_encode([
            'token' => $this->token,
            'title' => 'Ray.BiliBiliTool任务日报',
            'content' => str_replace(["\r\n", "\r", "\n"], "<br/>", $message),
            'topic' => $this->topic,
            'template' => 'html'
        ]);

        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => $json,
            ],
        ];

        $context  = stream_context_create($options);
        $response = file_get_contents($this->apiUrl, false, $context);
        
        return $response;
    }
}

$client = new PushPlusApiClient('your_token_here', 'your_topic_here');
$client->pushMessage("This is a test message.\r\nNew line.");
--EXPECT--
