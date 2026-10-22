--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/6)
--FILE--
<?php

class TopicParser {
    private $topicTokens;

    public function __construct($topic) {
        if (empty($topic)) {
            throw new InvalidArgumentException();
        }
        $this->topicTokens = explode('/', rtrim($topic, '/'));
    }

    public function getStatus($index) {
        if ($index <= 0 || $index > count($this->topicTokens)) {
            throw new InvalidArgumentException();
        }
        return $this->topicTokens[$index - 1] === '' ? null : $this->topicTokens[$index - 1];
    }

    public function getRequestId($index) {
        if ($index <= 0 || $index > count($this->topicTokens)) {
            throw new InvalidArgumentException();
        }
        parse_str(parse_url($this->topicTokens[$index - 1], PHP_URL_QUERY), $query);
        return isset($query['$rid']) ? $query['$rid'] : null;
    }

    public function getVersion($index) {
        if ($index <= 0 || $index > count($this->topicTokens)) {
            throw new InvalidArgumentException();
        }
        parse_str(parse_url($this->topicTokens[$index - 1], PHP_URL_QUERY), $query);
        return isset($query['$version']) ? $query['$version'] : null;
    }

    public function getMethodName($index) {
        if ($index <= 0 || $index > count($this->topicTokens)) {
            throw new InvalidArgumentException();
        }
        return $this->topicTokens[$index - 1] === '' ? null : $this->topicTokens[$index - 1];
    }
}

$validString = "$iothub/twin/res/";
$testParser = new TopicParser($validString);
$status = $testParser->getStatus(3);
$requestId = $testParser->getRequestId(3);
$version = $testParser->getVersion(3);
$methodName = $testParser->getMethodName(3);
--EXPECT--
