--TEST--
Translated seed (inferredbugs-csharp: phosphorusfive/2)
--FILE--
<?php
$inputs = [
    "\"abc\\n\"",
    "\"line1\nline2\"",
    "\"a\\x0041b\"",
    "\"a\"\"b\"",
    "\"unterminated",
    "\"cr\rx\""
];

foreach ($inputs as $s) {
    $len = strlen($s);
    $i = 1;
    $out = '';
    while ($i < $len) {
        $c = $s[$i++];
        if ($c === '"') {
            break;
        }
        if ($c === "\\") {
            if ($i >= $len) {
                break;
            }
            $e = $s[$i++];
            if ($e === '"') $out .= '"';
            elseif ($e === "'") $out .= "'";
            elseif ($e === "\\") $out .= "\\";
            elseif ($e === 'a') $out .= "\a";
            elseif ($e === 'b') $out .= "\b";
            elseif ($e === 'f') $out .= "\f";
            elseif ($e === 't') $out .= "\t";
            elseif ($e === 'v') $out .= "\v";
            elseif ($e === 'n') $out .= "\r\n";
            elseif ($e === 'r') {
                if ($i + 1 >= $len || $s[$i++] !== "\\" || $s[$i++] !== 'n') {
                    continue;
                }
                $out .= "\r\n";
            } elseif ($e === 'x') {
                $hex = substr($s, $i, 4);
                $i += 4;
                $out .= chr(intval($hex, 16));
            }
        } elseif ($c === "\n" || $c === "\r") {
            continue;
        } else {
            $out .= $c;
        }
    }
    if ($out !== '') {
        $out = str_replace("\n", "\r\n", $out);
    }
    echo $out, "\n";
}
?>
--EXPECT--
