--TEST--
Translated seed (inferredbugs-csharp: netmq/11)
--FILE--
<?php
$account = [
    'Id' => 1,
    'Name' => 'Test Account',
    'Number' => '11223',
    'Balance' => 0,
];
$action = [
    'Type' => 'Credit',
    'Amount' => 10,
];
$actionJson = json_encode($action);
$accountJson = json_encode($account);

$decodedAction = json_decode($actionJson, true);
$decodedAccount = json_decode($accountJson, true);

if (($decodedAction['Type'] ?? null) === 'Credit') {
    $decodedAccount['Balance'] += $decodedAction['Amount'];
}

echo json_encode($decodedAccount), PHP_EOL;
?>
--EXPECT--
