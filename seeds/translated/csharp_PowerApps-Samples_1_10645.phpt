--TEST--
Translated seed (inferredbugs-csharp: PowerApps-Samples/1)
--FILE--
<?php
$typedValue = "1";
$orgs = [
    ["FriendlyName" => "EnvA", "Endpoints" => ["WebApplication" => "https://a.example", "OrganizationService" => "https://a.example/org"]],
    ["FriendlyName" => "EnvB", "Endpoints" => ["WebApplication" => "https://b.example", "OrganizationService" => "https://b.example/org"]],
];

$number = 0;
foreach ($orgs as $organization) {
    $number++;
    $webAppUrl = $organization["Endpoints"]["WebApplication"];
    echo $number . " Name: " . $organization["FriendlyName"] . " URL: " . $webAppUrl . PHP_EOL;
}

try {
    $selected = (int)$typedValue;
    if ($selected <= $number) {
        $org = $orgs[$selected - 1];
        echo "You selected '" . $org["FriendlyName"] . "'" . PHP_EOL;
        $userId = bin2hex(random_bytes(8));
        echo "Your UserId for " . $org["FriendlyName"] . " is: " . $userId . PHP_EOL;
    } else {
        throw new OutOfRangeException("The selected value is not valid.");
    }
} catch (OutOfRangeException $e) {
    echo $e->getMessage() . PHP_EOL;
} catch (Throwable $e) {
    echo "Unable to process value: " . $typedValue . PHP_EOL;
}
?>
--EXPECT--
