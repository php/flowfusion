--TEST--
Translated seed (inferredbugs-csharp: msgraph-sdk-dotnet/10)
--FILE--
<?php
$ctx = new stdClass();
$retry = new stdClass();
$retry->ShouldRetry = fn($response) => true;
$retry->MaxRetry = 3;
$redirect = new stdClass();
$redirect->MaxRedirects = 4;

$request = new stdClass();
$request->url = "https://foo.bar";
$request->properties = [];
$request->properties[stdClass::class] = $ctx;
$request->properties['RetryOption'] = $retry;
$request->properties['RedirectOption'] = $redirect;

$response = new stdClass();

if (!($request->properties[stdClass::class] instanceof stdClass)) {
    throw new Exception();
}
if (!($request->properties['RetryOption']->ShouldRetry)($response)) {
    throw new Exception();
}
if ($request->properties['RetryOption']->MaxRetry !== 3) {
    throw new Exception();
}
if ($request->properties['RedirectOption']->MaxRedirects !== 4) {
    throw new Exception();
}
?>
--EXPECT--
