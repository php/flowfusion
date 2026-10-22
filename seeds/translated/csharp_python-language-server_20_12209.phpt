--TEST--
Translated seed (inferredbugs-csharp: python-language-server/20)
--FILE--
<?php
$s = "def f(x):\n    return\n\ndef g(y):\n    return y * 2\n";
$s = substr_replace($s, " ", 10, 0);
$s = substr_replace($s, "g", 11, 0);
$s = substr_replace($s, "(", 12, 0);
$s = substr_replace($s, "x", 13, 0);
$s = substr_replace($s, ")", 14, 0);
$s = substr_replace($s, "x * 2", 20, 1);
$s = substr_replace($s, "300", 24, 1);
$s = substr_replace($s, "#", 7, 0);
$s = substr_replace($s, "", 13, 9);

$s = "\nline1\nline2\nline3\nline4\n";
$s = substr_replace($s, "", 23, 1);
$s = substr_replace($s, "", 17, 1);
$s = substr_replace($s, "", 11, 1);
$s = substr_replace($s, "", 5, 1);

$s = "\nline\nline\nline\nline\n";
$s = substr_replace($s, "4", 20, 0);
$s = substr_replace($s, "3", 15, 0);
$s = substr_replace($s, "2", 10, 0);
$s = substr_replace($s, "1", 5, 0);

$s = "\nline1\nline2\nline3\nline4\n";
$s = substr_replace($s, "", 16, 5);
$s = substr_replace($s, "", 4, 5);

$s = "\nline1\nline2\nline3\nline4\n";
$s = substr_replace($s, "", 4, 5);
$s = substr_replace($s, "", 10, 5);

$s = "linelinelineline";
$s = substr_replace($s, "\n", 0, 0);
$s = substr_replace($s, "1\n", 9, 0);
$s = substr_replace($s, "2\r", 14, 0);
$s = substr_replace($s, "3\r\n", 19, 0);
$s = substr_replace($s, "4\r\n", 24, 0);

foreach (["", "\r\r", "\n\n", "\r\n\n\r", "a\r\nb\r\n c\r d\ne"] as $t) {
    preg_match_all("/\r\n|\r|\n/", $t, $m, PREG_OFFSET_CAPTURE);
    $n = count($m[0]);
    for ($i = 0; $i < $n; $i++) {
        $k = $m[0][$i][0];
        $e = $m[0][$i][1] + strlen($k);
    }
}
?>
--EXPECT--
