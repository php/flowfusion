--TEST--
Translated seed (inferredbugs-csharp: QuestPDF/12)
--FILE--
<?php
$doc = null;
$pageSpacing = 20.0;
$isGeneratingDocument = false;

$renderer = new class {
    public $pageSpacing = 20.0;
    public $isRendering = false;
    public $renderException = null;
    public $bounds = ['width' => 0.0, 'height' => 0.0];
    public $picture = null;
    public $document = null;

    public function updateDocument($document) {
        $this->document = $document;
        $this->isRendering = true;
        $this->renderException = null;
        $this->bounds = ['width' => 0.0, 'height' => 0.0];
        $this->picture = null;
        if ($document === 'throw') {
            $this->renderException = new Exception("boom");
        } elseif ($document !== null) {
            $n = is_int($document) ? $document : 1;
            $this->bounds = ['width' => $n * 10.0 + $this->pageSpacing, 'height' => $n * 5.0 + $this->pageSpacing];
            $this->picture = "picture:$n";
        }
        $this->isRendering = false;
    }
};

$renderer->pageSpacing = (float)$pageSpacing;
$renderer->updateDocument($doc);

$pageSpacing = 0.0;
$renderer->pageSpacing = (float)$pageSpacing;
$renderer->updateDocument(3);

$isGeneratingDocument = $renderer->isRendering;
if ($renderer->isRendering) {
    exit(0);
}

if ($renderer->renderException !== null) {
    $exceptionMsg = get_class($renderer->renderException) . "\n" . $renderer->renderException->getMessage();
    $text = "Exception occured:\n" . $exceptionMsg;
    $centerX = ($renderer->bounds['width'] - strlen($text)) / 2;
    $centerY = ($renderer->bounds['height'] - 2) / 2;
    echo $centerX . ":" . $centerY . "\n";
    exit(0);
}

$width = $renderer->bounds['width'];
$height = $renderer->bounds['height'];

if ($renderer->picture === null) {
    echo $width . "," . $height . "\n";
    exit(0);
}

echo $renderer->picture . "@" . $width . "x" . $height . "\n";
?>
--EXPECT--
