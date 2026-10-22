--TEST--
Translated seed (inferredbugs-csharp: SilentNotes/8)
--FILE--
<?php
$cases = [
    [100, 8, "<p>abc</p>"],
    [1, 2, "<p>abc</p><p>def</p><p>ghi</p><p>jkl</p><p>mno</p>"],
    [1, 3, "<p>abc</P><P>def</p><p>ghi</p><P>jkl</P>"],
    [1, 100, "<p>abc</p><p>def</p><p>ghi</p>"],
    [1, 3, "<p>abc</p><p>def</p><p>ghi</p>"],
    [1, 2, "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body><p>abc</p><p>def</p><p>ghi</p></body></html>"],
    [1, 2, "<p>abc</br>def</p></br><p><strong>ghi</strong></p><p>jkl</p>"],
    [1, 2, "<p class='ql qt' spellcheck='false'>abc</p><p disabled>def</p><p>ghi</p>"],
    [1, 2, "<p>abc<li>def</li></p><p><li><li>hi</li></li>ghi</p><p>klm</p>"],
    [1, 2, "<p>abc<li>def</li><p>ghi</p><p>klm</p>"],
    [1, 3, "<ul><li>111</li><li>222</li><li>333</li><li>444</li><li>555</li></ul>"],
];

foreach ($cases as [$min, $wanted, $html]) {
    if ($html === null) {
        continue;
    }
    $out = $html;
    if (strlen($out) < $min) {
        continue;
    }
    if (preg_match_all('/<\s*\/?\s*([a-z0-9]+)\b[^>]*>/i', $out, $m, PREG_OFFSET_CAPTURE)) {
        $count = 0;
        $cut = strlen($out);
        foreach ($m[1] as $i => $tag) {
            if (!preg_match('/^(p|li|ul|ol|strong)$/i', $tag[0])) {
                continue;
            }
            if (preg_match('/^<\s*\//', substr($out, $m[0][$i][1]))) {
                continue;
            }
            $count++;
            if ($count === $wanted + 1) {
                $cut = $m[0][$i][1];
                break;
            }
        }
        $out = substr($out, 0, $cut);
        $out = preg_replace('/^<!DOCTYPE html><html><head>.*?<body>/is', '', $out);
        $out = preg_replace('/<\/body>\s*<\/html>\s*$/i', '', $out);
    }
}
?>
--EXPECT--
