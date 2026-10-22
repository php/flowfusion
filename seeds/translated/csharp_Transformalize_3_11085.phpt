--TEST--
Translated seed (inferredbugs-csharp: Transformalize/3)
--FILE--
<?php
$rows = [
    ['x' => 'Dale', 'y' => 'Newman'],
    ['x' => 3, 'y' => 11],
    ['FirstName' => 'Dale', 'LastName' => 'Newman'],
    ['FirstName' => 'Dale', 'LastName' => 'Newman'],
    ['FirstName' => 'Dale', 'LastName' => 'Newman'],
    ['FirstName' => 'Dale', 'LastName' => 'Newman'],
];

$rows[0]['z'] = sprintf('%s %s', $rows[0]['x'], $rows[0]['y']);
$rows[1]['z'] = $rows[1]['x'] * $rows[1]['y'];
$rows[2]['FullName'] = $rows[2]['FirstName'] . ' ' . $rows[2]['LastName'];
$rows[3]['FullName'] = implode(' ', [$rows[3]['FirstName'], $rows[3]['LastName']]);
$rows[4]['FullName'] = strtr('@{ var fullName = Model["FirstName"] + " " + Model["LastName"];}@fullName', [
    '@{ var fullName = Model["FirstName"] + " " + Model["LastName"];}' => $rows[4]['FirstName'] . ' ' . $rows[4]['LastName'],
    '@fullName' => $rows[4]['FirstName'] . ' ' . $rows[4]['LastName'],
]);
$rows[5]['FullName'] = strtr('@{ var fullName = Model.FirstName + " " + Model.LastName;}@fullName', [
    '@{ var fullName = Model.FirstName + " " + Model.LastName;}' => $rows[5]['FirstName'] . ' ' . $rows[5]['LastName'],
    '@fullName' => $rows[5]['FirstName'] . ' ' . $rows[5]['LastName'],
]);

echo $rows[0]['z'], "\n";
echo $rows[1]['z'], "\n";
echo $rows[2]['FullName'], "\n";
echo $rows[3]['FullName'], "\n";
echo $rows[4]['FullName'], "\n";
echo $rows[5]['FullName'], "\n";
?>
--EXPECT--
