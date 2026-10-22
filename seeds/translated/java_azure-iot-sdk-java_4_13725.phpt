--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/4)
--FILE--
<?php

class TopicParser
{
    private $topicTokens;

    public function __construct($topic)
    {
        if (empty($topic)) {
            throw new InvalidArgumentException();
        }
        $this->topicTokens = explode('/', $topic);
    }

    public function getStatus($index)
    {
        if ($index <= 0 || $index >= count($this->topicTokens)) {
            throw new InvalidArgumentException();
        }
        return $this->topicTokens[$index] ?: throw new InvalidArgumentException();
    }

    public function getRequestId($index)
    {
        if ($index <= 0 || $index >= count($this->topicTokens)) {
            throw new InvalidArgumentException();
        }
        preg_match('/\$(rid)=(\d+)/', $this->topicTokens[$index], $matches);
        return $matches[2] ?? null;
    }

    public function getVersion($index)
    {
        if ($index <= 0 || $index >= count($this->topicTokens)) {
            throw new InvalidArgumentException();
        }
        preg_match('/\$(version)=(\d+)/', $this->topicTokens[$index], $matches);
        return $matches[2] ?? null;
    }

    public function getMethodName($index)
    {
        if ($index <= 0 || $index >= count($this->topicTokens)) {
            throw new InvalidArgumentException();
        }
        if (empty($this->topicTokens[$index])) {
            throw new InvalidArgumentException();
        }
        return $this->topicTokens[$index];
    }
}

$validString = '$iothub/methods/res/methodName';
$parser = new TopicParser($validString);
$methodName = $parser->getMethodName(3);

$validString = '$iothub/twin/res/?$version=7&$rid=5';
$parser = new TopicParser($validString);
$requestId = $parser->getRequestId(3);
$version = $parser->getVersion(3);
--EXPECT--
