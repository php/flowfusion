--TEST--
Translated seed (inferredbugs-java: EasyCaptcha/1)
--FILE--
<?php

class SpecCaptcha {
    public function text() {
        return "captcha_text";
    }

    public function out($outputStream) {
        fwrite($outputStream, "This would be an image output for SpecCaptcha");
        fclose($outputStream);
    }
}

class GifCaptcha {
    public function __construct($width, $height, $length) {
        // Initialization code
    }

    public function text() {
        return "captcha_gif_text";
    }

    public function out($outputStream) {
        fwrite($outputStream, "This would be an image output for GifCaptcha");
        fclose($outputStream);
    }
}

function runTests() {
    $specCaptcha = new SpecCaptcha();
    echo $specCaptcha->text() . PHP_EOL;
    $specCaptcha->out(fopen("D:/a/aa.png", "w"));

    $gifCaptcha = new GifCaptcha(130, 48, 5);
    echo $gifCaptcha->text() . PHP_EOL;
    $gifCaptcha->out(fopen("D:/a/aa.gif", "w"));
}

runTests();

?>
--EXPECT--
