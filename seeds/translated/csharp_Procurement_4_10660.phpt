--TEST--
Translated seed (inferredbugs-csharp: Procurement/4)
--FILE--
<?php
$h = fopen('php://temp', 'r+');
fwrite($h, "<html><head><title>Test Thread</title></head><body><input name=\"forum_thread\" value=\"ABC123\"><input name=\"forum_post\" value=\"XYZ789\"><input name=\"hash\" value=\"Q1W2E3\"></body></html>");
rewind($h);
$html = stream_get_contents($h);

preg_match('/<title\b[^>]*>\s*(?<Title>[\s\S]*?)<\/title>/i', $html, $m1);
preg_match('/name=\"forum_thread\" value=\"(?<hash>[a-zA-Z0-9]{1,})\"/', $html, $m2);
preg_match('/name=\"forum_post\" value=\"(?<hash>[a-zA-Z0-9]{1,})\"/', $html, $m3);
preg_match('/name=\"hash\" value=\"(?<hash>[a-zA-Z0-9]{1,})\"/', $html, $m4);

$title = strtolower($m1['Title'] ?? '');
$needle = strtolower('Test Thread');
if (strpos($title, $needle) === false) {
    $x = $m2['hash'] ?? '';
    $y = $m3['hash'] ?? '';
    $z = $m4['hash'] ?? '';
}

fclose($h);
?>
--EXPECT--
