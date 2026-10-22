--TEST--
Translated seed (inferredbugs-java: EasyCaptcha/3)
--FILE--
<?php

class SpecCaptcha {
    public function text() {
        return rand(1000, 9999); // Simple numeric captcha
    }

    public function out($filename) {
        file_put_contents($filename, "Image content for " . $this->text());
    }
}

class GifCaptcha {
    public function text() {
        return rand(1000, 9999); // Simple numeric captcha
    }

    public function out($filename) {
        file_put_contents($filename, "GIF content for " . $this->text());
    }
}

class ChineseCaptcha {
    public function text() {
        return "汉字"; // Chinese characters as captcha
    }

    public function out($filename) {
        file_put_contents($filename, "Image content for " . $this->text());
    }
}

class ChineseGifCaptcha {
    public function text() {
        return "汉字"; // Chinese characters as captcha
    }

    public function out($filename) {
        file_put_contents($filename, "GIF content for " . $this->text());
    }
}

function runTests() {
    for ($i = 0; $i < 5; $i++) {
        $specCaptcha = new SpecCaptcha();
        echo $specCaptcha->text() . PHP_EOL;
        $specCaptcha->out("D:/Php/aa" . $i . ".png");
    }

    for ($i = 0; $i < 5; $i++) {
        $gifCaptcha = new GifCaptcha();
        echo $gifCaptcha->text() . PHP_EOL;
        $gifCaptcha->out("D:/Php/aa" . $i . ".gif");
    }

    $chineseCaptcha = new ChineseCaptcha();
    echo $chineseCaptcha->text() . PHP_EOL;
    $chineseCaptcha->out("D:/Php/aa.png");

    $chineseGifCaptcha = new ChineseGifCaptcha();
    echo $chineseGifCaptcha->text() . PHP_EOL;
    $chineseGifCaptcha->out("D:/Php/aa.gif");
}

runTests();

?>
--EXPECT--
