--TEST--
Translated seed (inferredbugs-csharp: QuestPDF/13)
--FILE--
<?php
$doc = null;
$pageSpacing = 20.0;
$isGeneratingDocument = false;
$renderer = (object)[
    'pageSpacing' => (float)$pageSpacing,
    'isRendering' => false,
    'renderException' => null,
    'bounds' => (object)['width' => 0.0, 'height' => 0.0],
    'picture' => null,
];
$updateDocument = function (&$renderer, $document) use (&$pageSpacing) {
    $renderer->isRendering = false;
    $renderer->renderException = null;
    if ($document === null) {
        $renderer->bounds->width = 0.0;
        $renderer->bounds->height = 0.0;
        $renderer->picture = null;
        return;
    }
    if (is_string($document) && $document === 'boom') {
        $renderer->renderException = new Exception("boom");
        return;
    }
    $renderer->bounds->width = 640.0 + $pageSpacing;
    $renderer->bounds->height = 480.0 + $pageSpacing;
    $renderer->picture = ['document' => $document, 'spacing' => $pageSpacing];
};
$render = function (&$renderer, &$isGeneratingDocument) {
    $isGeneratingDocument = $renderer->isRendering;
    if ($renderer->isRendering) return;
    if ($renderer->renderException !== null) {
        $msg = "Exception occured:\n" . get_class($renderer->renderException) . "\n" . $renderer->renderException->getMessage();
        echo $msg . PHP_EOL;
        return;
    }
    $width = $renderer->bounds->width;
    $height = $renderer->bounds->height;
    if ($renderer->picture === null) return;
    echo "Render " . $width . "x" . $height . " spacing=" . $renderer->picture['spacing'] . PHP_EOL;
};
$invalidateDocument = function (&$renderer, &$doc, &$pageSpacing) use (&$updateDocument) {
    $updateDocument($renderer, $doc);
};
$updateDocument($renderer, $doc);
$render($renderer, $isGeneratingDocument);
$pageSpacing = 0.0;
$renderer->pageSpacing = (float)$pageSpacing;
$doc = "sample";
$invalidateDocument($renderer, $doc, $pageSpacing);
$render($renderer, $isGeneratingDocument);
$doc = "boom";
$invalidateDocument($renderer, $doc, $pageSpacing);
$render($renderer, $isGeneratingDocument);
?>
--EXPECT--
