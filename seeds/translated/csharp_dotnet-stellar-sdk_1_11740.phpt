--TEST--
Translated seed (inferredbugs-csharp: dotnet-stellar-sdk/1)
--FILE--
<?php
$input = "Dollar Sign $";
$bytes = pack('N', strlen($input)) . $input;
$pad = (4 - (strlen($input) % 4)) % 4;
if ($pad) $bytes .= str_repeat("\0", $pad);
$len = unpack('N', substr($bytes, 0, 4))[1];
$output = substr($bytes, 4, $len);
echo $output;
?>
--EXPECT--
