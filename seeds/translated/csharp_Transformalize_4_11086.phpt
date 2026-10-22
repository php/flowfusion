--TEST--
Translated seed (inferredbugs-csharp: Transformalize/4)
--FILE--
<?php
$rows = [
    ['x' => 'Dale', 'y' => 'Newman'],
    ['x' => 3, 'y' => 11],
    ['FirstName' => 'Dale', 'LastName' => 'Newman'],
];

$rows[0]['z'] = sprintf('%s %s', $rows[0]['x'], $rows[0]['y']);
$rows[1]['z'] = $rows[1]['x'] * $rows[1]['y'];
$rows[2]['FullName'] = $rows[2]['FirstName'] . ' ' . $rows[2]['LastName'];

foreach ($rows as $row) {
    $out[] = $row;
}
?>
--EXPECT--
