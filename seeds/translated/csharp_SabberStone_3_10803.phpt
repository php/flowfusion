--TEST--
Translated seed (inferredbugs-csharp: SabberStone/3)
--FILE--
<?php
$state = ['id' => 4, 'oop' => 0, 'current' => 0, 'first' => 0, 'turn' => 0];
$players = [
    ['id' => 1, 'name' => 'P1', 'current' => 0, 'first' => 0, 'frozen' => true, 'attacks' => 0, 'summoned' => false, 'charge' => false],
    ['id' => 2, 'name' => 'P2', 'current' => 0, 'first' => 0, 'frozen' => true, 'attacks' => 0, 'summoned' => true, 'charge' => true],
];
$players[0]['first'] = 1;
$players[0]['current'] = 1;
$state['current'] = 1;
$state['first'] = 1;
$state['turn'] = 1;

for ($i = 0; $i < 2; $i++) {
    $players[$i]['drawn'] = 0;
    $players[$i]['mana'] = 0;
    $players[$i]['temp_mana'] = 0;
    $players[$i]['overload_locked'] = 0;
    $players[$i]['overload_owed'] = 0;
    $players[$i]['turns_left'] = 1;
}

for ($i = 0; $i < 3; $i++) {
    $state['id']++;
    $state['oop']++;
}

$clone = $state;
$clone['id'] = $state['id'];
$clone['oop'] = $state['oop'];

foreach ($players as &$p) {
    $p['drawn'] += 3;
    if ($p['id'] === 2) $p['drawn']++;
    $p['turns_left'] = 1;
}
unset($p);

foreach ($players as &$p) {
    $p['attacks'] = 0;
    $p['frozen'] = false;
    $p['summoned'] = false;
}
unset($p);

$players[0]['current'] = 0;
$players[1]['current'] = 1;
$state['current'] = 2;
$state['turn']++;

echo "";
?>
--EXPECT--
