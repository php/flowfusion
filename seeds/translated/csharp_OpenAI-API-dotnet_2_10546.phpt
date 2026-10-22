--TEST--
Translated seed (inferredbugs-csharp: OpenAI-API-dotnet/2)
--FILE--
<?php
file_put_contents('.openai', "OPENAI_KEY=pk-test12\nOPENAI_SECRET_KEY: sk-test34");
putenv('OPENAI_KEY=pk-test-env');
putenv('OPENAI_SECRET_KEY=sk-test-env');

$envKey = getenv('OPENAI_KEY');
$envSecret = getenv('OPENAI_SECRET_KEY');

$lines = @file('.openai', FILE_IGNORE_NEW_LINES);
$fileKey = null;
$fileSecret = null;
foreach ($lines ?: [] as $line) {
    if (str_starts_with($line, 'OPENAI_KEY=')) $fileKey = substr($line, 11);
    if (str_starts_with($line, 'OPENAI_SECRET_KEY:')) $fileSecret = ltrim(substr($line, 18));
}

$authKey = $envKey !== false ? $envKey : $fileKey;
$authSecret = $envSecret !== false ? $envSecret : $fileSecret;
$defaultAuth = [$authKey, $authSecret];

$manualAuth = ['pk-testAA', 'sk-testBB'];
$apiAuth = $defaultAuth;
$apiAuth = $manualAuth;
$auth1 = $apiAuth;

$key = 'sk-testBB';
$key = 'pk-testAA';
$key2 = null;
$key2 = 'pk-testAA';
$key3 = null;
$key3 = 'sk-testBB';
$key4 = null;
$key4 = 'sk-testBB';

echo "";
?>
--EXPECT--
