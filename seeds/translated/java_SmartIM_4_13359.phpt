--TEST--
Translated seed (inferredbugs-java: SmartIM/4)
--FILE--
<?php

class WechatMessage {
    const MSGTYPE_TEXT = 1;
    const MSGTYPE_IMAGE = 3;
    const MSGTYPE_EMOTICON = 47;
    const MSGTYPE_APP = 49;

    public $MsgType;
    public $Content;
    public $text;

    public function __construct($msgType, $content) {
        $this->MsgType = $msgType;
        $this->Content = $content;
    }

    public function getText() {
        if ($this->text === null) {
            $this->parseContent();
        }
        return $this->text;
    }

    public function parseContent() {
        $temp = htmlspecialchars_decode($this->Content);
        if ($this->MsgType == self::MSGTYPE_EMOTICON) {
            $this->text = "Emoji: " . $temp;
        } elseif ($this->MsgType == self::MSGTYPE_IMAGE) {
            $this->text = "Image: " . $temp;
        } elseif ($this->MsgType == self::MSGTYPE_APP) {
            $this->text = "App Message: " . $temp;
        } else {
            $this->text = $temp;
        }
    }
}

$wechatMessage = new WechatMessage(WechatMessage::MSGTYPE_EMOTICON, "<emoji>😀</emoji>");
echo $wechatMessage->getText();

$wechatMessage = new WechatMessage(WechatMessage::MSGTYPE_IMAGE, "<img src='image.jpg'/>");
echo $wechatMessage->getText();

$wechatMessage = new WechatMessage(WechatMessage::MSGTYPE_APP, "<app>Hello</app>");
echo $wechatMessage->getText();
--EXPECT--
