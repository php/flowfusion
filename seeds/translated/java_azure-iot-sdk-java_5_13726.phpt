--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/5)
--FILE--
<?php

class TopicParser {
    private $topicTokens;

    public function __construct($topic) {
        if (empty($topic)) {
            throw new InvalidArgumentException("Topic cannot be null or empty.");
        }
        $this->topicTokens = explode('/', rtrim($topic, '/'));
    }

    public function getStatus($tokenIndex) {
        if ($tokenIndex <= 0 || $tokenIndex > count($this->topicTokens)) {
            throw new InvalidArgumentException("Invalid token index.");
        }
        return $this->topicTokens[$tokenIndex - 1] ?? throw new InvalidArgumentException("Status cannot be null.");
    }

    public function getRequestId($tokenIndex) {
        if ($tokenIndex <= 0 || $tokenIndex > count($this->topicTokens)) {
            throw new InvalidArgumentException("Invalid token index.");
        }
        parse_str(parse_url($this->topicTokens[$tokenIndex - 1], PHP_URL_QUERY), $query);
        return $query['$rid'] ?? null;
    }

    public function getVersion($tokenIndex) {
        if ($tokenIndex <= 0 || $tokenIndex > count($this->topicTokens)) {
            throw new InvalidArgumentException("Invalid token index.");
        }
        parse_str(parse_url($this->topicTokens[$tokenIndex - 1], PHP_URL_QUERY), $query);
        return $query['$version'] ?? null;
    }

    public function getMethodName($tokenIndex) {
        if ($tokenIndex <= 0 || $tokenIndex > count($this->topicTokens)) {
            throw new InvalidArgumentException("Invalid token index.");
        }
        return $this->topicTokens[$tokenIndex - 1] ?? throw new InvalidArgumentException("Method name cannot be null.");
    }
}

$topicString = "$iothub/twin/res/";
$parser = new TopicParser($topicString);
$status = $parser->getStatus(1);
$requestId = $parser->getRequestId(1);
$version = $parser->getVersion(1);
$methodName = $parser->getMethodName(1);
--EXPECT--
