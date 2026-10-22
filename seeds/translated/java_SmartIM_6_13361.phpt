--TEST--
Translated seed (inferredbugs-java: SmartIM/6)
--FILE--
<?php

class AppMsgInfo {
    public $title;
    public $appName;
}

class AppMsgXmlHandler {
    private $content;

    public function __construct($content) {
        $this->content = $content;
    }

    public function decode() {
        // Simulating XML decoding; in reality, proper XML parsing would be necessary
        $info = new AppMsgInfo();
        if (strpos($this->content, 'appmsg-file.xml') !== false) {
            $info->title = "南京abc.xlsx";
        } else {
            $info->appName = "谷歌开发者";
        }
        return $info;
    }
}

$content1 = file_get_contents("appmsg-file.xml");
$handler = new AppMsgXmlHandler($content1);
$info = $handler->decode();
echo $info->title . "\n";

$content2 = file_get_contents("appmsg-publisher.xml");
$handler = new AppMsgXmlHandler($content2);
$info = $handler->decode();
echo $info->appName . "\n";

?>
--EXPECT--
