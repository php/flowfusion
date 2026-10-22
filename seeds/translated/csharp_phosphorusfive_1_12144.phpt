--TEST--
Translated seed (inferredbugs-csharp: phosphorusfive/1)
--FILE--
<?php
$viewState = [];
$newJavaScriptObjects = [];
$newCssFiles = [];

$registerJavaScriptFile = function (string $url) use (&$viewState, &$newJavaScriptObjects) {
    if (!isset($viewState['__p5_js_objects']))
        $viewState['__p5_js_objects'] = [];
    foreach ($viewState['__p5_js_objects'] as $idx) {
        if ($idx[0] === $url)
            return;
    }
    $viewState['__p5_js_objects'][] = [$url, true];
    $newJavaScriptObjects[] = [$url, true];
};

$registerJavaScript = function (string $script) use (&$viewState, &$newJavaScriptObjects) {
    if (!isset($viewState['__p5_js_objects']))
        $viewState['__p5_js_objects'] = [];
    foreach ($viewState['__p5_js_objects'] as $idx) {
        if ($idx[0] === $script)
            return;
    }
    $viewState['__p5_js_objects'][] = [$script, false];
    $newJavaScriptObjects[] = [$script, true];
};

$registerStylesheetFile = function (string $url) use (&$viewState, &$newCssFiles) {
    if (!isset($viewState['__p5_css_files']))
        $viewState['__p5_css_files'] = [];
    if (!in_array($url, $viewState['__p5_css_files'], true)) {
        $viewState['__p5_css_files'][] = $url;
        $newCssFiles[] = $url;
    }
};

$registerJavaScriptFile('a.js');
$registerJavaScriptFile('a.js');
$registerJavaScript('a.js');
$registerJavaScript('b(){return 1;}');
$registerStylesheetFile('a.css');
$registerStylesheetFile('a.css');

echo count($viewState['__p5_js_objects']) . "\n";
echo count($newJavaScriptObjects) . "\n";
echo count($viewState['__p5_css_files']) . "\n";
echo count($newCssFiles) . "\n";
?>
--EXPECT--
