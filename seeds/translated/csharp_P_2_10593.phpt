--TEST--
Translated seed (inferredbugs-csharp: P/2)
--FILE--
<?php
$activeDirectory = __DIR__;
$tmpFile = $activeDirectory . DIRECTORY_SEPARATOR . 'check-tmp.txt';
$accDir = $activeDirectory . DIRECTORY_SEPARATOR . 'acc';
@mkdir($accDir);
file_put_contents($tmpFile, "alpha\nbeta\n");
file_put_contents($accDir . DIRECTORY_SEPARATOR . 'acc_0.txt', "alpha\nbeta\n");
file_put_contents($accDir . DIRECTORY_SEPARATOR . 'acc_1.txt', "alpha\ngamma\n");
$found = false;
foreach (glob($accDir . DIRECTORY_SEPARATOR . 'acc*.txt') as $fi) {
    if (is_readable($fi) && is_readable($tmpFile) && file_get_contents($fi) === file_get_contents($tmpFile)) {
        $found = true;
        break;
    }
}
if (!$found) {
    $nextId = 0;
    while (file_exists($accDir . DIRECTORY_SEPARATOR . "acc_{$nextId}.txt")) {
        $nextId++;
    }
    copy($tmpFile, $accDir . DIRECTORY_SEPARATOR . "acc_{$nextId}.txt");
}
@unlink($tmpFile);
?>
--EXPECT--
