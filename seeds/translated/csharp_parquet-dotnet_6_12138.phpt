--TEST--
Translated seed (inferredbugs-csharp: parquet-dotnet/6)
--FILE--
<?php
$stream = fopen('php://temp', 'w+b');
if ($stream === false) {
    exit(1);
}

$magic = "PAR1";
fwrite($stream, $magic);

$schema = ["fields" => [["name" => "id", "type" => "int64"]]];
$footer = ["schema" => $schema, "rows" => 0];

$existingSchema = $footer["schema"];
if ($schema != $existingSchema) {
    exit(1);
}

fseek($stream, 0, SEEK_END);
fwrite($stream, pack('V', 0));
fwrite($stream, $magic);

fflush($stream);
fclose($stream);
?>
--EXPECT--
