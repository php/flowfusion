--TEST--
Translated seed (inferredbugs-csharp: Binance/1)
--FILE--
<?php

class BinanceWebSocketStream {
    private $uri;
    private $isStreaming = false;
    private $messageCallback;

    public static function createUri($streamName = null, $additionalStreamName = null) {
        if (is_null($streamName)) {
            return null;
        }
        if (!is_null($additionalStreamName)) {
            return "https://api.binance.com/stream?streams={$streamName}/{$additionalStreamName}";
        }
        return "https://api.binance.com/ws/{$streamName}";
    }

    public function setUri($uri) {
        $this->uri = $uri;
    }

    public function getUri() {
        return $this->uri;
    }

    public function getIsStreaming() {
        return $this->isStreaming;
    }

    public function streamAsync() {
        $this->isStreaming = true;
        // Simulate an asynchronous operation
        sleep(1);
        $this->isStreaming = false;
    }

    public function onMessage($callback) {
        $this->messageCallback = $callback;
    }

    public function triggerMessage($subject, $json) {
        if ($this->messageCallback) {
            call_user_func($this->messageCallback, (object) ['subject' => $subject, 'json' => $json]);
        }
    }
}

$streamName = "test";
$uri = BinanceWebSocketStream::createUri($streamName);
$stream = new BinanceWebSocketStream();

$stream->setUri($uri);
$stream->setUri($uri); // can set to same URI.
$stream->setUri(null); // can set to null.

$stream->setUri($uri);

$messageReceived = false;
$stream->onMessage(function ($event) use (&$messageReceived, $streamName) {
    $messageReceived = ($event->subject === $streamName && $event->json === "{}");
});

$stream->triggerMessage($streamName, "{}");
$stream->streamAsync();
if ($stream->getIsStreaming()) {
    // Wait until streaming is done
    sleep(1);
}
$stream->triggerMessage($streamName, "{}");

exit(0);
--EXPECT--
