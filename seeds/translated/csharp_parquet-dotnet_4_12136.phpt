--TEST--
Translated seed (inferredbugs-csharp: parquet-dotnet/4)
--FILE--
<?php
$path = tempnam(sys_get_temp_dir(), 'parq');
$f = fopen($path, 'w+b');
fwrite($f, "PAR1");
fseek($f, 0, SEEK_END);
$rows = [["a" => 1, "b" => "x"], ["a" => 2, "b" => "y"], ["a" => 3, "b" => "z"], ["a" => 4, "b" => "w"], ["a" => 5, "b" => "v"]];
$schema = array_keys($rows[0]);
$groupSize = 2;
$offset = 0;
$written = false;
do {
    $count = min($groupSize, count($rows) - $offset);
    $chunk = array_slice($rows, $offset, $count);
    foreach ($schema as $col) {
        $values = array_column($chunk, $col);
        $payload = serialize($values);
        fwrite($f, pack('V', strlen($payload)));
        fwrite($f, $payload);
    }
    $offset += $groupSize;
    $written = true;
} while ($offset < count($rows));
if ($written) {
    $meta = serialize(['rows' => count($rows), 'schema' => $schema]);
    fwrite($f, pack('V', strlen($meta)));
    fwrite($f, $meta);
    fwrite($f, "PAR1");
}
fflush($f);
fclose($f);
unlink($path);
--EXPECT--
