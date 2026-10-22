--TEST--
Translated seed (inferredbugs-csharp: sentakki/1)
--FILE--
<?php
declare(strict_types=1);

$mods = [];
$type = 'Automation';
switch ($type) {
    case 'Automation':
        $mods = ['maimaiModAutoplay'];
        break;
    default:
        $mods = [null];
        break;
}

$defaultKeyBindings = [
    ['key' => 'Z', 'action' => 'Button1'],
    ['key' => 'X', 'action' => 'Button2'],
];

$resourceStore = [];
$textureStore = ['Textures/coin' => 'coin'];
$icon = $textureStore['Textures/coin'] ?? null;

echo "Washing machine rhythm game.\n";
echo "maimai\n";
echo implode(',', array_map(static fn($m) => $m ?? 'null', $mods)) . "\n";
echo implode(',', array_map(static fn($b) => $b['key'] . ':' . $b['action'], $defaultKeyBindings)) . "\n";
echo ($icon ?? 'null') . "\n";
--EXPECT--
