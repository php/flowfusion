--TEST--
Translated seed (inferredbugs-csharp: prose/1)
--FILE--
<?php
$csv = "\n# this is a header comment\ncol1, col2, col3\n1, 2, A\n3, 4, B\n# mid file comment\n5, 6, C";
$fw = "\ncol1 col2 col3\n1    2    A\n3    4    B\n\n5    6    C";
$csvOverride = "\n1,2;3\n4,5;6\n7,8;9";

foreach ([$csv, $fw, $csvOverride] as $input) {
    $lines = preg_split("/\r\n|\n|\r/", trim($input));
    $rows = [];
    $delimiter = null;
    $isFw = false;

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, ';') !== false) $delimiter = ';';
        if ($delimiter !== null) {
            $rows[] = array_map('trim', explode($delimiter, $line));
        } elseif (preg_match('/\s{2,}/', $line)) {
            $isFw = true;
            $rows[] = preg_split('/\s+/', $line);
        } else {
            $rows[] = array_map('trim', explode(',', $line));
        }
    }

    if ($isFw) {
        echo "Skip: 0\n";
        echo "Column names: " . implode(', ', $rows[0]) . "\n";
        echo "New-line strings: \"\\n\"\n";
        echo "Field positions: (1, 5), (6, 10), (11, 11)\n";
        for ($i = 1; $i < min(4, count($rows)); $i++) echo implode(' | ', $rows[$i]) . "\n";
        echo "\n";
    } else {
        echo "Skip: 0\n";
        echo "Column names: " . implode(', ', $rows[0]) . "\n";
        echo "New-line strings: \"\\n\"\n";
        echo "Delimiter: " . ($delimiter ?? ',') . "\n";
        echo "Quote char: \"\n";
        for ($i = 1; $i < min(4, count($rows)); $i++) echo implode(' | ', $rows[$i]) . "\n";
        echo "\n";
    }
}
?>
--EXPECT--
