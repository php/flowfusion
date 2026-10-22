--TEST--
Translated seed (inferredbugs-csharp: microsoft-authentication-library-for-dotnet/2)
--FILE--
<?php
$u = getenv('USER');
if ($u === false || trim($u) === '') $u = getenv('USERNAME');
if ($u === false || trim($u) === '') $u = '';
$u = strtolower($u);
$h = base64_encode(hash('sha256', $u, true));
echo $h, PHP_EOL;
?>
--EXPECT--
