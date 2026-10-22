--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/6)
--FILE--
<?php
$items = [];
for ($i = 0; $i < 3; $i++) {
    $items[] = ['name' => 'label' . ($i + 1)];
}
$items[] = ['name' => 'label10'];

$used = [];
foreach ($items as $it) {
    $used[$it['name']] = true;
}

$type = 'Label';
$number = 1;
while (isset($used[strtolower($type) . $number])) {
    $number++;
}
echo strtolower($type) . $number . PHP_EOL;
--EXPECT--
