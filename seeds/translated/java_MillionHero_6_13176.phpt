--TEST--
Translated seed (inferredbugs-java: MillionHero/6)
--FILE--
<?php
$adbPath = getcwd() . '/target/classes/adb/adb';
$imagePath = 'D:/Photo';

class OCRFactory {
    public function getOcr($choice) {
        return $choice == 1 ? new TessOCR() : new BaiduOCR();
    }
}

class PatternFactory {
    public function getPattern($choice, $ocr, $utils) {
        return $choice == 1 ? new MillionHeroPattern($ocr, $utils) : new ClashPattern($ocr, $utils);
    }
}

class Utils {
    public function __construct($adbPath, $imagePath) {
        // Initialization logic can be added here
    }
}

class TessOCR {}
class BaiduOCR {}
class MillionHeroPattern {
    public function __construct($ocr, $utils) {}
    public function run() {
        // Simulate pattern run
    }
}
class ClashPattern {
    public function __construct($ocr, $utils) {}
    public function run() {
        // Simulate pattern run
    }
}

$ocrFactory = new OCRFactory();
$patternFactory = new PatternFactory();
$utils = new Utils($adbPath, $imagePath);

echo "请选择您要使用的文字识别方式\n1.TessOCR\n2.百度OCR\n默认使用TessOCR，选择后回车: ";
$ocrChoice = intval(trim(fgets(STDIN))) ?: 1;
$ocr = $ocrFactory->getOcr($ocrChoice);

echo "请选择您要进入的游戏\n1.百万英雄\n2.冲顶大会\n默认为百万英雄，选择后回车: ";
$patternChoice = intval(trim(fgets(STDIN))) ?: 1;
$pattern = $patternFactory->getPattern($patternChoice, $ocr, $utils);

while (true) {
    $str = trim(fgets(STDIN));
    if ($str === 'exit') {
        echo "ヾ(￣▽￣)Bye~Bye~\n";
        break;
    } elseif (strlen($str) === 0) {
        echo "开始答题\n";
        $pattern->run();
    }
}
?>
--EXPECT--
