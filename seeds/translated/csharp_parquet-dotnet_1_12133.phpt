--TEST--
Translated seed (inferredbugs-csharp: parquet-dotnet/1)
--FILE--
<?php
$fp = fopen('php://temp', 'w+b');
fseek($fp, 0, SEEK_END);
fwrite($fp, "PAR1");
$schema = ["definitions" => [1, null, 2, null, 3]];
$values = $schema["definitions"];
$defs = [];
$nonnull = [];
foreach ($values as $v) {
    if ($v === null) {
        $defs[] = 0;
    } else {
        $defs[] = 1;
        $nonnull[] = $v;
    }
}
$payload = pack('C*', ...$defs) . implode(',', array_map('strval', $nonnull));
$compressed = gzencode($payload);
fwrite($fp, $compressed);
$meta = pack('V', strlen($compressed)) . pack('V', strlen($payload));
fwrite($fp, $meta);
fwrite($fp, pack('V', strlen($meta)));
fwrite($fp, "PAR1");
fflush($fp);
?>
--EXPECT--
