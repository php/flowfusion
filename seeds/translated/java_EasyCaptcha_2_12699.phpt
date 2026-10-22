--TEST--
Translated seed (inferredbugs-java: EasyCaptcha/2)
--FILE--
<?php

class Captcha {
    const TYPE_ONLY_NUMBER = 1;

    private $charType;
    private $text;

    public function setCharType($type) {
        $this->charType = $type;
        $this->generateText();
    }

    private function generateText() {
        if ($this->charType === self::TYPE_ONLY_NUMBER) {
            $this->text = (string)mt_rand(1000, 9999);
        }
    }

    public function text() {
        return $this->text;
    }

    public function output($filename) {
        file_put_contents($filename, "Dummy image data for {$this->text}\n");
    }
}

for ($i = 0; $i < 100; $i++) {
    $specCaptcha = new Captcha();
    $specCaptcha->setCharType(Captcha::TYPE_ONLY_NUMBER);
    echo $specCaptcha->text() . "\n";
    $specCaptcha->output("D:/a/aa{$i}.png");
}

$gifCaptcha = new Captcha();
$gifCaptcha->setCharType(Captcha::TYPE_ONLY_NUMBER);
echo $gifCaptcha->text() . "\n";
// $gifCaptcha->output("D:/a/aa.gif");
--EXPECT--
