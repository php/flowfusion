--TEST--
Translated seed (inferredbugs-csharp: platyPS/9)
--FILE--
<?php
$inputs = [
    "   \xE2\x80\x93Title\r\n\r\nParagraph with **bold** and *italic* and [link](https://example.com)\n\n```line1\nline2```\r\n",
    "\n\n==\r\nUnderlined\r\n--\r\n",
    "Text  \r\nnext\r\n\r\nmore",
];

foreach ($inputs as $s) {
    $s = ltrim($s);
    $s = str_replace("–", "-", $s);
    $s = preg_replace("/([^\r])\n/", "\r\n", $s);

    while ($s !== '') {
        $bestPos = null;
        $bestLen = 0;
        $bestType = '';

        $patterns = [
            'hash_header' => '/(\#+)[ ]*(.+?)[ ]*\#*(\r\n)+/',
            'underline_header' => '/(.+?)[ ]*\r\n(==+|--+)[ ]*(\r\n)+/',
            'tick_codeblock' => '/```(\r\n)*((.|\r\n)+?)```(\r\n)*/',
            'hardbreak' => '/[ ]{2}\r\n/',
            'softbreak' => '/(\r\n){1}/',
            'new_paragraph' => '/(\r\n){2,}/',
            'hyperlink' => '/\[(.+?)\]\((https?:\/\/[^\'"><\s]+)?\)/',
            'bold' => '/\*\*(.+?)\*\*/',
            'italic' => '/\*(.+?)\*/',
        ];

        foreach ($patterns as $type => $re) {
            if (preg_match($re, $s, $m, PREG_OFFSET_CAPTURE)) {
                $pos = $m[0][1];
                $len = strlen($m[0][0]);
                if ($bestPos === null || $pos < $bestPos) {
                    $bestPos = $pos;
                    $bestLen = $len;
                    $bestType = $type;
                }
            }
        }

        if ($bestPos === null) {
            $s = '';
            continue;
        }

        $s = substr($s, $bestPos + $bestLen);
    }
}
?>
--EXPECT--
