--TEST--
Translated seed (inferredbugs-csharp: Transformalize/5)
--FILE--
<?php
$rows = [['x' => 'Dale', 'y' => 'Newman', 'FirstName' => 'Dale', 'LastName' => 'Newman']];

foreach ($rows as &$row) {
    $row['z'] = sprintf('%s %s', $row['x'], $row['y']);
    $row['n'] = $row['x'] * $row['y'];
    $row['FullName1'] = $row['FirstName'] . ' ' . $row['LastName'];
    $row['FullName2'] = implode(' ', [$row['FirstName'], $row['LastName']]);
    $row['FullName3'] = eval('return $row["FirstName"] . " " . $row["LastName"];');
    $row['FullName4'] = eval('return $row["FirstName"] . " " . $row["LastName"];');
}
unset($row);

echo $rows[0]['z'], "\n";
echo $rows[0]['n'], "\n";
echo $rows[0]['FullName1'], "\n";
echo $rows[0]['FullName2'], "\n";
echo $rows[0]['FullName3'], "\n";
echo $rows[0]['FullName4'], "\n";
--EXPECT--
