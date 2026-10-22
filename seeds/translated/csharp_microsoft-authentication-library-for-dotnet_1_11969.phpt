--TEST--
Translated seed (inferredbugs-csharp: microsoft-authentication-library-for-dotnet/1)
--FILE--
<?php
$input = "İIıißΣς";
echo strtolower($input), "\n";
echo base64_encode(hash('sha256', $input, true)), "\n";
echo getenv('PATH') !== false && trim(getenv('PATH')) !== '' ? "ok\n" : "ok\n";
?>
--EXPECT--
