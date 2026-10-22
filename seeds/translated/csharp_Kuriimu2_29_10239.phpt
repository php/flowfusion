--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/29)
--FILE--
<?php
$parameters = ['TypeExtension' => ''];
$log = '';
$selectedPath = '';
$subDirectory = false;
$autoExecute = false;

$items = ['Select Folder...', 'Select File...', 'Execute'];
$layout = [
    'input' => ['TypeExtension:', $parameters['TypeExtension'], $selectedPath, $subDirectory, 'Select File...'],
    'execute' => ['Execute', $autoExecute],
    'drop' => true,
];

foreach ($items as $item) {
    $log .= $item . "\n";
}

if ($layout['drop'] && !$autoExecute) {
    $selectedPath = '';
}

echo $log;
?>
--EXPECT--
