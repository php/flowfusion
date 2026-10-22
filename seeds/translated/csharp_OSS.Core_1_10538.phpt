--TEST--
Translated seed (inferredbugs-csharp: OSS.Core/1)
--FILE--
<?php
class AppIdentity {
    public ?string $ask_func = null;
}

$appIdentity = new AppIdentity();
$funcCode = "FUNC_123";

if ($appIdentity->ask_func === null || $appIdentity->ask_func === '') {
    $appIdentity->ask_func = $funcCode;
}

echo $appIdentity->ask_func, PHP_EOL;
--EXPECT--
