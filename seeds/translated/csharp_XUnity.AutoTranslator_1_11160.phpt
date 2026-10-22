--TEST--
Translated seed (inferredbugs-csharp: XUnity.AutoTranslator/1)
--FILE--
<?php
$endpoint = new class {
    public $setup = false;
    function isSettingUp() { return $this->setup; }
    function startSetup() { $this->setup = true; return null; }
    function endSetup($y) { $this->setup = false; }
    function getServiceUrl($u, $f, $t) { return "data:text/plain," . rawurlencode($u . ":" . $f . ":" . $t); }
    function applyHeaders(&$h) { $h['X-Test'] = '1'; }
    function tryExtractTranslated($r, &$out) { $out = $r; return true; }
    function getMaxConcurrency() { return 2; }
    function fallback() { return false; }
};

$runningTranslations = 0;
$translationCount = 0;
$maxConcurrency = $endpoint->getMaxConcurrency();
$headers = [];

for ($i = 0; $i < 205; $i++) {
    $yieldable = $endpoint->startSetup();
    if ($yieldable !== null) {
        $endpoint->endSetup($yieldable);
    }

    $url = $endpoint->getServiceUrl("u$i", "en", "ja");
    $endpoint->applyHeaders($headers);
    $runningTranslations++;
    $result = @file_get_contents($url);
    $runningTranslations--;

    $translationCount++;

    if ($translationCount > 100 && $maxConcurrency > 1) {
        $maxConcurrency = 1;
    }

    if ($translationCount % 100 === 0) {
        $headers = [];
    }

    if ($result !== false) {
        $translatedText = null;
        if ($endpoint->tryExtractTranslated($result, $translatedText)) {
            $translatedText = $translatedText ?? '';
            $translatedText;
        }
    }
}
?>
--EXPECT--
