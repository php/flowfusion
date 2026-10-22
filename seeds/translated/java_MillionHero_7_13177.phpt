--TEST--
Translated seed (inferredbugs-java: MillionHero/7)
--FILE--
<?php

$adbPath = "D:\\adb";
$imagePath = "D:\\Photo";
$patternSelection = 1;
$searchSelection = 1;

$configPath = dirname(__FILE__) . "/hero.config";

if (!file_exists($configPath)) {
    file_put_contents($configPath, "测试");
} else {
    echo "nothing\n";
}

$width = 500;
$height = 800;

echo "Config path: $configPath\n";

$adrPathLabel = "adb路径：$adbPath\n";
$imagePathLabel = "图片存放路径：$imagePath\n";
$ocrSelectionLabel = "OCR方式：TessOCR / BaiDuOCR\n";
$searchSelectionLabel = "搜索方式：百度 / 搜狗\n";
$patternSelectionLabel = "游戏模式：百万英雄 / 冲顶大会\n";

echo $adrPathLabel . $imagePathLabel . $ocrSelectionLabel . $searchSelectionLabel . $patternSelectionLabel;
--EXPECT--
