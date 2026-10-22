--TEST--
Translated seed (inferredbugs-java: SmartIM/2)
--FILE--
<?php

class WechatMessage {
    const MSGTYPE_TEXT = 1;
    const MSGTYPE_IMAGE = 3;
    const MSGTYPE_APP = 49;
    const MSGTYPE_EMOTICON = 47;

    public $MsgType;
    public $Content;
    public $text;

    public function __construct($MsgType, $Content) {
        $this->MsgType = $MsgType;
        $this->Content = $Content;
    }

    public function getText() {
        if ($this->text === null) {
            $this->parseContent();
        }
        return $this->text;
    }

    public function parseContent() {
        $temp = htmlspecialchars($this->Content);
        switch ($this->MsgType) {
            case self::MSGTYPE_EMOTICON:
                $this->text = "Emoji: " . $temp;
                break;
            case self::MSGTYPE_IMAGE:
                $this->text = "Image: " . $temp;
                break;
            case self::MSGTYPE_APP:
                $this->text = "App message: " . $temp;
                break;
            default:
                $this->text = $temp;
                break;
        }
    }
}

$message = new WechatMessage(WechatMessage::MSGTYPE_IMAGE, "<img src='test.jpg' />");
echo $message->getText();
--EXPECT--
