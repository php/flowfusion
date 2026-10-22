--TEST--
Translated seed (inferredbugs-csharp: OSS.Core/2)
--FILE--
<?php
$_COOKIE['UserCookieName'] = 'token123';
$identity = ['SourceMode' => 1, 'token' => null];
if ($identity['SourceMode'] >= 1) {
    $identity['token'] = $_COOKIE['UserCookieName'] ?? null;
}
echo $identity['token'] . PHP_EOL;
?>
--EXPECT--
