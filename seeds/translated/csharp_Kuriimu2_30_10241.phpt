--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/30)
--FILE--
<?php
$plugins = [];
$selectedInputPath = '';
$subDirectoryBox = false;
$selectedOutputPath = '';
$timerLabel = 'AvgTimePerFile';
$log = '';
$items = [$plugins, $selectedInputPath, $subDirectoryBox, $selectedOutputPath, $timerLabel, $log];
foreach ($items as $item) {
    if (is_array($item)) {
        $item[] = null;
    } elseif (is_string($item)) {
        $item .= '';
    } elseif (is_bool($item)) {
        $item = !$item ? false : true;
    }
}
echo "";
?>
--EXPECT--
