--TEST--
Translated seed (inferredbugs-csharp: SilentNotes/7)
--FILE--
<?php
$content = str_repeat("<div><p>abc</p></div>", 30) . "<div><p>tail</p></div>";
$min = 400;
$wantedTags = 8;
$wantedLength = 400;

if ($content === null || strlen($content) <= $min) {
    echo $content;
    exit;
}

$tagNames = [];
$firstPos = null;
$tagCount = 0;
$offset = 0;

while (preg_match('/<[^>]+>/', $content, $m, PREG_OFFSET_CAPTURE, $offset)) {
    $tag = $m[0][0];
    $pos = $m[0][1];
    $len = strlen($tag);
    $offset = $pos + $len;

    $tagContent = trim($tag, "<>/ \t");
    $p = strcspn($tagContent, "<>/ \t");
    if ($p > 0) $tagContent = substr($tagContent, 0, $p);
    $tagName = strtolower($tagContent);

    if (in_array($tagName, ["p", "h1", "h2", "h3", "li", "div", "dt", "dd", "blockquote", "pre", "table"], true)) {
        if (strncmp($tag, "</", 2) !== 0) {
            $tagNames[] = $tagName;
            if ($firstPos === null) $firstPos = $pos;
        } else {
            $top = end($tagNames);
            if ($top === $tagName) {
                array_pop($tagNames);
                if (!$tagNames) {
                    $tagCount++;
                    $length = $pos + $len - $firstPos;
                    if ($tagCount >= $wantedTags || $length >= $wantedLength) {
                        echo substr($content, $firstPos, $length);
                        exit;
                    }
                }
            }
        }
    }
}

echo $content;
--EXPECT--
