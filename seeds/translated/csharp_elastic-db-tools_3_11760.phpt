--TEST--
Translated seed (inferredbugs-csharp: elastic-db-tools/3)
--FILE--
<?php
$connectionString = "Server=localhost;Database=test;Trusted_Connection=True;";
$credential = null;
$accessTokenFactory = function () {
    return "token_" . str_repeat("x", 3);
};

$info = (object)[
    "ConnectionString" => $connectionString,
    "Credential" => $credential,
    "AccessTokenFactory" => $accessTokenFactory,
];

$conn = (object)[
    "ConnectionString" => $info->ConnectionString,
    "Credential" => $info->Credential,
];

if ($info->AccessTokenFactory !== null) {
    $conn->AccessToken = ($info->AccessTokenFactory)();
}

$clone = (object)[
    "ConnectionString" => "Server=localhost;Database=other;",
    "Credential" => $info->Credential,
    "AccessTokenFactory" => $info->AccessTokenFactory,
];

$conn2 = (object)[
    "ConnectionString" => $clone->ConnectionString,
    "Credential" => $clone->Credential,
];

if ($clone->AccessTokenFactory !== null) {
    $conn2->AccessToken = ($clone->AccessTokenFactory)();
}

echo $conn->ConnectionString . "\n";
echo $conn->AccessToken . "\n";
echo $conn2->ConnectionString . "\n";
echo $conn2->AccessToken . "\n";
?>
--EXPECT--
