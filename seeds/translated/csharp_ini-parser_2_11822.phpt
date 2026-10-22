--TEST--
Translated seed (inferredbugs-csharp: ini-parser/2)
--FILE--
<?php
$buffer = '';

function trim_buffer(string $s): string {
    return trim($s);
}

$buffer = '';
$buffer = '';
$buffer = "hello world!";
$buffer = "   hello world!  ";
$buffer = trim_buffer($buffer);
$buffer = "    ";
$buffer = trim_buffer($buffer);
$buffer = "   hello world!\n";
$buffer = trim_buffer($buffer);
$pos = strpos("hello wor world!", "world");
$pos = strpos("hello wor world!", "");
$pos = strpos("hello wor world!", "d!");
$buffer = "    hello wor world!   ";
$buffer = trim_buffer($buffer);
$pos = strpos($buffer, "world");
$pos = strpos($buffer, "");
$pos = strpos($buffer, "d!");
$pos = strpos($buffer, " ");
$pos = strpos($buffer, "!");
$buffer = "    hello world!";
$buffer = trim_buffer($buffer);
$buffer = substr($buffer, 3, 7);
$buffer = substr($buffer, 0, 1);
$buffer = '';
$buffer = '';
$buffer = '';
$buffer = "hello world!";
$buffer = substr($buffer, 0, 5);
$buffer = substr($buffer, 0, 50);
$buffer = substr($buffer, 0, -1);
$buffer = "   hello world!";
$buffer = trim_buffer($buffer);
$buffer = substr($buffer, 5, 7);
$buffer = substr($buffer, 1, 2);
$buffer = "hello world!";
$buffer = substr($buffer, 6, 1);
$buffer = "hello world!";
$buffer = "  hello world! ";
$buffer = trim_buffer($buffer);
$buffer = substr($buffer, 3, 6);
$buffer = "  hello world! ";
$buffer = "hello world!";
$buffer = "  h";
$buffer = str_starts_with("  hello world! ", "  h") ? "ok" : "no";
$buffer = str_starts_with("  hello world! ", "hel") ? "ok" : "no";
?>
--EXPECT--
