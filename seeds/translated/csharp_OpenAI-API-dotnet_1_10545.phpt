--TEST--
Translated seed (inferredbugs-csharp: OpenAI-API-dotnet/1)
--FILE--
<?php
file_put_contents('.openai', "OPENAI_KEY=pk-test12\nOPENAI_SECRET_KEY: sk-test34");
putenv('OPENAI_KEY=pk-test-env');
putenv('OPENAI_SECRET_KEY=sk-test-env');

$env = [
    'OPENAI_KEY' => getenv('OPENAI_KEY') ?: null,
    'OPENAI_SECRET_KEY' => getenv('OPENAI_SECRET_KEY') ?: null,
];

$file = file_exists('.openai') ? file('.openai', FILE_IGNORE_NEW_LINES) : [];
$cfg = [];
foreach ($file as $line) {
    if (preg_match('/^\s*([^:=\s]+)\s*[:=]\s*(.*?)\s*$/', $line, $m)) $cfg[$m[1]] = $m[2];
}

$auth = ['APIKey' => $env['OPENAI_KEY'], 'Secretkey' => $env['OPENAI_SECRET_KEY']];
$defaultAuth = $auth;
$manualAuth = ['APIKey' => 'pk-testAA', 'Secretkey' => 'sk-testBB'];
$apiAuth = $defaultAuth;
$apiAuth = $manualAuth;

$key1 = 'sk-testBB';
$key2 = 'pk-testAA';
$key3 = 'pk-testCC';
$key4 = 'sk-testDD';

if (($auth['APIKey'] ?? null) !== 'pk-test-env') exit(1);
if (($auth['Secretkey'] ?? null) !== 'sk-test-env') exit(1);
if (($cfg['OPENAI_KEY'] ?? null) !== 'pk-test12') exit(1);
if (($cfg['OPENAI_SECRET_KEY'] ?? null) !== 'sk-test34') exit(1);
if (($defaultAuth['APIKey'] ?? null) !== 'pk-test-env') exit(1);
if (($defaultAuth['Secretkey'] ?? null) !== 'sk-test-env') exit(1);
if (($apiAuth['APIKey'] ?? null) !== 'pk-testAA') exit(1);
if (($apiAuth['Secretkey'] ?? null) !== 'sk-testBB') exit(1);
if ($key1 !== 'sk-testBB') exit(1);
if ($key2 !== 'pk-testAA') exit(1);
if ($key3 !== 'pk-testCC') exit(1);
if ($key4 !== 'sk-testDD') exit(1);
?>
--EXPECT--
