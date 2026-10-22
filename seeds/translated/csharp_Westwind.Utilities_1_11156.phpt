--TEST--
Translated seed (inferredbugs-csharp: Westwind.Utilities/1)
--FILE--
<?php
date_default_timezone_set('UTC');

$state = new stdClass();
$state->Email = 'rstrahl@west-wind.com';
$state->UserId = '1';
$state->IsAdmin = true;
$state->Name = 'Rick Strahl | Markus Egger';
$state->Date = date('c');
$state->NullString = null;
$state->Role = (object)['Name' => 'Rick', 'Level' => 10];

$json = json_encode($state, JSON_UNESCAPED_SLASHES);
for ($i = 0; $i < 1; $i++) {
    $json = json_encode($state, JSON_UNESCAPED_SLASHES);
}
echo "Json: " . strlen($json) . "\n";
echo $json . "\n";

$state2 = json_decode($json);
echo $state2->Email . "\n";
echo $state2->UserId . "\n";
echo $state2->Name . "\n";
echo ($state2->NullString === null ? "null\n" : "notnull\n");
echo date('i', strtotime($state->Date)) . "\n";
echo intval($state2->UserId) . "\n";
echo ($state2->Role === null ? "null\n" : "notnull\n");

$state = null;
$json = json_encode($state);
echo strlen($json) . "\n";
echo $json . "\n";
$state2 = json_decode($json);
var_dump($state2);
?>
--EXPECT--
