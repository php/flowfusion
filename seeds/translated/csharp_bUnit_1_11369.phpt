--TEST--
Translated seed (inferredbugs-csharp: bUnit/1)
--FILE--
<?php
$setup = function (&$state) {
    $state['items'][] = 'x';
    $state['meta']['count'] = count($state['items']);
};

$setupAsync = function (&$state) {
    $state['items'][] = 'y';
    $state['meta']['count'] = count($state['items']);
};

$render = function (array $state): string {
    return implode('', array_map(
        fn($v) => '<span>' . htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</span>',
        $state['items']
    ));
};

$state = ['items' => ['a'], 'meta' => ['count' => 1]];
$setup($state);
$setupAsync($state);

$inputHtml = $render($state);
$expectedState = ['items' => ['a', 'x', 'y'], 'meta' => ['count' => 3]];
$expectedHtml = $render($expectedState);

libxml_use_internal_errors(true);
$inputDom = new DOMDocument();
$expectedDom = new DOMDocument();
$inputDom->loadHTML($inputHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
$expectedDom->loadHTML($expectedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

$input = $inputDom->saveHTML();
$expected = $expectedDom->saveHTML();

if ($input !== $expected) {
    throw new RuntimeException('Snapshot test failed.');
}
?>
--EXPECT--
