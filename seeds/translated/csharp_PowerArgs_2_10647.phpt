--TEST--
Translated seed (inferredbugs-csharp: PowerArgs/2)
--FILE--
<?php
$scene = [];
$mainCharacter = ['x' => 3, 'y' => 3];
$scene[] = ['type' => 'enemy', 'x' => 8, 'y' => 8];
$scene[] = ['type' => 'weapon', 'kind' => 'RPGLauncher', 'ammo' => 100000, 'x' => 20, 'y' => 15];
$scene[] = ['type' => 'weapon', 'kind' => 'Pistol', 'ammo' => 100000, 'x' => 15, 'y' => 15];
for ($y = 2; $y < 17; $y++) {
    $scene[] = ['type' => 'wall', 'x' => 60, 'y' => $y];
}
echo "";
?>
--EXPECT--
