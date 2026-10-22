--TEST--
Translated seed (inferredbugs-java: SmartIM/5)
--FILE--
<?php

$content = file_get_contents("appmsg-file.xml");
$handler = new AppMsgXmlHandler($content);
$info = $handler->decode();
echo $info->title . PHP_EOL;

$handler = new AppMsgXmlHandler(file_get_contents("appmsg-publisher.xml"));
$info = $handler->decode();
echo $info->appName . PHP_EOL;

class AppMsgXmlHandler {
    private $content;

    public function __construct($content) {
        $this->content = $content;
    }

    public function decode() {
        $xml = simplexml_load_string($this->content);
        $info = new stdClass();
        $info->title = (string)$xml->title;
        $info->appName = (string)$xml->appName;
        return $info;
    }
}
--EXPECT--
