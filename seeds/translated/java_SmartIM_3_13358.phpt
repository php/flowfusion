--TEST--
Translated seed (inferredbugs-java: SmartIM/3)
--FILE--
<?php

class WechatMessage {
    const MSGTYPE_TEXT = 1;
    const MSGTYPE_IMAGE = 3;
    const MSGTYPE_EMOTICON = 47;

    public $MsgType;
    public $Content;
    public $text;

    public function __construct($msgType, $content) {
        $this->MsgType = $msgType;
        $this->Content = $content;
        $this->text = null;
    }

    public function getText() {
        if ($this->text === null) {
            $this->parseContent();
        }
        return $this->text;
    }

    public function parseContent() {
        $temp = htmlspecialchars($this->Content);
        if ($this->MsgType === self::MSGTYPE_EMOTICON) {
            $this->text = "<em>$temp</em>";
        } elseif ($this->MsgType === self::MSGTYPE_IMAGE) {
            $this->text = "<img src='$temp' />";
        } else {
            $this->text = $temp;
        }
    }
}

$message = new WechatMessage(WechatMessage::MSGTYPE_EMOTICON, "Hello <world>!");
echo $message->getText();
--EXPECT--
