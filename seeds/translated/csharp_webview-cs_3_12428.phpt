--TEST--
Translated seed (inferredbugs-csharp: webview-cs/3)
--FILE--
<?php
$html = "<html><body><h1>Eval</h1><div id='content'></div></body></html>";
$doc = new DOMDocument();
@$doc->loadHTML($html);
$xp = new DOMXPath($doc);

$timeout = microtime(true);
$duration = 1.0;
$tick = 0;

for ($i = 0; $i < 3; $i++) {
    if (microtime(true) > $timeout) {
        $timeout = microtime(true) + $duration;
        $nodes = $xp->query("//*[@id='content']");
        if ($nodes && $nodes->length) {
            $nodes->item(0)->textContent = 'Time:' . date('c');
        }
    }

    $title = 'Eval - tick:' . $tick++;
}

echo $doc->saveHTML();
?>
--EXPECT--
