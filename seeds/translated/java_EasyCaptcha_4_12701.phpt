--TEST--
Translated seed (inferredbugs-java: EasyCaptcha/4)
--FILE--
<?php

class SpecCaptcha {
    public function text() {
        return rand(1000, 9999); // Generate a four-digit random number
    }
    
    public function out($filename) {
        file_put_contents($filename, "Captcha Image for: " . $this->text());
    }
}

class GifCaptcha {
    public function text() {
        return rand(1000, 9999); // Generate a four-digit random number
    }
    
    public function out($filename) {
        file_put_contents($filename, "Gif Captcha Image for: " . $this->text());
    }
}

class ChineseCaptcha {
    public function text() {
        return "验证码"; // A Chinese character for "verification code"
    }
    
    public function out($filename) {
        file_put_contents($filename, "Chinese Captcha Image for: " . $this->text());
    }
}

class ChineseGifCaptcha {
    public function text() {
        return "验证码"; // A Chinese character for "verification code"
    }
    
    public function out($filename) {
        file_put_contents($filename, "Chinese Gif Captcha Image for: " . $this->text());
    }
}

function main() {
    for ($i = 0; $i < 5; $i++) {
        $specCaptcha = new SpecCaptcha();
        echo $specCaptcha->text() . "\n";
        $specCaptcha->out("D:/Php/aa" . $i . ".png");
    }

    for ($i = 0; $i < 5; $i++) {
        $gifCaptcha = new GifCaptcha();
        echo $gifCaptcha->text() . "\n";
        $gifCaptcha->out("D:/Php/aa" . $i . ".gif");
    }
    
    $chineseCaptcha = new ChineseCaptcha();
    echo $chineseCaptcha->text() . "\n";
    $chineseCaptcha->out("D:/Php/aa.png");
    
    $chineseGifCaptcha = new ChineseGifCaptcha();
    echo $chineseGifCaptcha->text() . "\n";
    $chineseGifCaptcha->out("D:/Php/aa.gif");
}

main();
--EXPECT--
