--TEST--
Translated seed (inferredbugs-csharp: serilog/1)
--FILE--
<?php
$default = "{TimeStamp} [{Level}] {Message:l}{NewLine:l}{Exception:l}";
$templates = [
    $default,
    "{TimeStamp} [{Level}] {Message}",
    "{Message:l}{NewLine:l}{Exception:l}",
];
$levels = ["Minimum", "Debug", "Information", "Warning", "Error", "Fatal"];
$paths = [
    "log.txt",
    "dump.txt",
    "Logs/myapp-{0}.log",
];
foreach ($templates as $t) {
    foreach ($levels as $l) {
        foreach ($paths as $p) {
            $s = strtr($t, ["{TimeStamp}" => date("c"), "{Level}" => $l, "{Message:l}" => "hello", "{NewLine:l}" => PHP_EOL, "{Exception:l}" => ""]);
            if (strpos($p, "{0}") !== false) {
                $p = sprintf($p, date("Y-m-d"));
            }
            file_put_contents($p, $s . PHP_EOL, FILE_APPEND);
        }
    }
}
echo "ok\n";
?>
--EXPECT--
