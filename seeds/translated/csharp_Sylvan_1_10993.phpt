--TEST--
Translated seed (inferredbugs-csharp: Sylvan/1)
--FILE--
<?php
$cases = [
    "A,B\r\n1,2\r\n1,\r",
    "A,B\r\n1,2\r\n1,",
    "A,B\r\n1",
    "Id,Name,Value,Date\n1,John,Low,2000-11-11\n2,Jane,High,1989-03-14\n",
    "Id,Name,Value,Date\r\n1,John,Low,2000-11-11\r\n2,Jane,High,1989-03-14\r\n",
    "a,b,c\n1,\"\"\"2\"\", (two)\",3",
    "a,b,b",
    "a,a,b,b,c,c",
    "Id\r1\r\n2\n3\n\r",
    "Name,Value\r\nTest,\r\n",
    "Name,Value\r\nTest",
    "Name,Value\r\n\r\n",
    "Name,Value\r\n,,\r\n",
    "Name,Value\r\nA\"\"B,\"And\"more,\r\n",
    "Bool\nT\nF\nX\n",
    "Date\n20200803\n20200804\n20200805\n",
    "A;B;C;D;E\n1;2;3;4;5\n",
    "A|B,(b)|C|D|E\n1|2|3|4|5\n",
    "a,b,c\n1,2,3\n4,5\n6,7,8\n",
    "Name;Value1;Value2\nTest;2,08;0,82\n",
];
foreach ($cases as $data) {
    $rows = preg_split("/\r\n|\n|\r/", $data);
    $rows = array_values(array_filter($rows, fn($x) => $x !== null && $x !== ''));
    if (!$rows) continue;
    $delims = [',', ';', '|'];
    $best = ',';
    $bestCount = -1;
    foreach ($delims as $d) {
        $c = substr_count($rows[0], $d);
        if ($c > $bestCount) { $bestCount = $c; $best = $d; }
    }
    $f = str_getcsv($rows[0], $best);
    for ($i = 1; $i < count($rows); $i++) str_getcsv($rows[$i], $best);
    if (count($f) && $rows[0] === "Id,Name,Value,Date") {
        $x = $f[0];
        $y = $f[1];
    }
}
?>
--EXPECT--
