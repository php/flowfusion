--TEST--
Translated seed (inferredbugs-csharp: sentry-dotnet/3)
--FILE--
<?php
$input = str_repeat("abc123\x00\xff", 1024) . "tail";
$levels = [-1, 0, 1, 6, 9];
foreach ($levels as $level) {
    $out = gzencode($input, $level);
    $decoded = gzdecode($out);
    if ($decoded !== $input) {
        throw new Exception("gzip roundtrip failed");
    }
}
$empty = gzencode("", 9);
if (gzdecode($empty) !== "") {
    throw new Exception("empty roundtrip failed");
}
echo "ok\n";
--EXPECT--
