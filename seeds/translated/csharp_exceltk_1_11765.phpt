--TEST--
Translated seed (inferredbugs-csharp: exceltk/1)
--FILE--
<?php
$src = "\\trowd\\cell  A \\cell \\b bold \\cell \\\\escaped\\\\ \\cell \\row\\trowd\\cell  B \\cell  C  \\cell \\row";
$rows = [];
$pos = 0;
while (true) {
    $rowEnd = stripos($src, '\\row', $pos);
    if ($rowEnd === false) break;
    if ($rowEnd > 0 && $src[$rowEnd - 1] === '\\') {
        $pos = $rowEnd + 1;
        continue;
    }
    $rowStart = stripos($src, '\\trowd', $rowEnd);
    if ($rowStart === false) break;
    if ($rowStart > 0 && $src[$rowStart - 1] === '\\') {
        $pos = $rowEnd + 1;
        continue;
    }
    $row = substr($src, $rowStart, $rowEnd - $rowStart);
    $cells = [];
    $cellStart = 0;
    while (true) {
        $cellEnd = stripos($row, '\\cell ', $cellStart);
        if ($cellEnd === false) break;
        if ($cellEnd > 0 && $row[$cellEnd - 1] === '\\') {
            $cellStart = $cellEnd + 1;
            continue;
        }
        $cell = substr($row, $cellStart, $cellEnd - $cellStart);
        $i = 0;
        while ($i < strlen($cell)) {
            $i = strpos($cell, '\\', $i);
            if ($i === false) break;
            if (isset($cell[$i + 1]) && $cell[$i + 1] === '\\') {
                $cell = substr_replace($cell, '', $i, 1);
                $i++;
            } else {
                $e = strpos($cell, ' ', $i);
                if ($e === false) $e = strlen($cell) - 1;
                $cell = substr_replace($cell, '', $i, $e - $i + 1);
            }
        }
        $cell = trim($cell);
        $cells[] = $cell;
        $cellStart = $cellEnd + 1;
    }
    if (!$rows) {
        $headers = $cells;
    } else {
        $rows[] = $cells;
    }
    if (!isset($headers)) $headers = $cells;
    $pos = $rowEnd + 1;
}
$out = [];
$out[] = implode("\t", $headers);
foreach ($rows as $r) $out[] = implode("\t", $r);
echo implode("\n", $out), "\n";
?>
--EXPECT--
