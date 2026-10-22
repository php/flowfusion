--TEST--
Translated seed (inferredbugs-csharp: RPGCore/2)
--FILE--
<?php
declare(strict_types=1);

$players = [
    ['id' => bin2hex(random_bytes(2)), 'board' => array_fill(0, 4, array_fill(0, 4, '.'))],
    ['id' => bin2hex(random_bytes(2)), 'board' => array_fill(0, 4, array_fill(0, 4, '.'))],
];
$current = 0;
$resources = [[], []];

$resources[0]['1'] = ['pos' => [1, 1], 'owner' => 0];
$resources[1]['1'] = ['pos' => [3, 3], 'owner' => 1];
$current = 1;

$resources[1]['2'] = ['pos' => [2, 1], 'owner' => 1];
$resources[0]['2'] = ['pos' => [2, 1], 'owner' => 0];
$current = 0;

$resources[0]['3'] = ['pos' => [1, 2], 'owner' => 0];
$resources[1]['3'] = ['pos' => [1, 2], 'owner' => 1];

foreach ($players as $i => $player) {
    echo "Player {$player['id']}\n";
    if ($current === $i) {
        echo "(Current players turn)\n";
    }
    for ($y = 0; $y < 4; $y++) {
        for ($x = 0; $x < 4; $x++) {
            $ch = '.';
            foreach ($resources as $owner => $rset) {
                foreach ($rset as $r) {
                    if ($r['owner'] === $i && $r['pos'][0] === $x && $r['pos'][1] === $y) {
                        $ch = (string)($owner + 1);
                    }
                }
            }
            echo $ch;
        }
        echo "\n";
    }
    echo "\n";
}

$players[0]['board'][2][2] = 'B';
$current = 1;

foreach ($players as $i => $player) {
    echo "Player {$player['id']}\n";
    if ($current === $i) {
        echo "(Current players turn)\n";
    }
    for ($y = 0; $y < 4; $y++) {
        for ($x = 0; $x < 4; $x++) {
            echo $players[$i]['board'][$y][$x] ?? '.';
        }
        echo "\n";
    }
    echo "\n";
}

echo "=======\n";
?>
--EXPECT--
