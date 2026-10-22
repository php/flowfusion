--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/7)
--FILE--
<?php

class TopicParser {
    private $topicTokens;

    public function __construct($topic) {
        if (empty($topic)) {
            throw new InvalidArgumentException();
        }
        $this->topicTokens = explode('/', $topic);
    }

    public function getStatus($index) {
        if ($index <= 0 || $index > count($this->topicTokens)) {
            throw new InvalidArgumentException();
        }
        $token = $this->topicTokens[$index - 1];
        if (is_null($token)) {
            throw new InvalidArgumentException();
        }
        return $token;
    }

    public function getRequestId($index) {
        if ($index <= 0 || $index > count($this->topicTokens)) {
            throw new InvalidArgumentException();
        }
        $token = $this->topicTokens[$index - 1];
        if (strpos($token, '$rid=') !== false) {
            return substr($token, strpos($token, '=') + 1);
        }
        return null;
    }
}

$validString = "$iothub/twin/res/";
$testParser = new TopicParser($validString);
$status = $testParser->getStatus(3);
$requestId = $testParser->getRequestId(3);
--EXPECT--
