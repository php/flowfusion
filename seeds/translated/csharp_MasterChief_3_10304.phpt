--TEST--
Translated seed (inferredbugs-csharp: MasterChief/3)
--FILE--
<?php
$domain = "example.com";
$userName = "user";
$userPassword = "pass";

$groups = [];
$raw = [
    "CN=Admins,OU=Groups,DC=example,DC=com",
    "CN= DevOps ,OU=Groups,DC=example,DC=com",
    "CN=QA-Team,OU=Groups,DC=example,DC=com",
    "CN= 12345 ,OU=Groups,DC=example,DC=com",
];

foreach ($raw as $group) {
    if (preg_match('/CN=\s*(?<g>\w*)\s*./', trim($group), $m)) {
        $groups[] = $m['g'];
    }
}

$login = false;
try {
    $uri = "ldap://{$domain}";
    $login = is_string($uri) && $userName !== '' && $userPassword !== '';
} catch (Throwable $e) {
    $login = false;
}

echo implode(",", $groups) . "\n";
echo ($login ? "1" : "0") . "\n";
--EXPECT--
