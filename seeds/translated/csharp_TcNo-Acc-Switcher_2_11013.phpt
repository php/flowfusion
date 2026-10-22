--TEST--
Translated seed (inferredbugs-csharp: TcNo-Acc-Switcher/2)
--FILE--
<?php
$task = 'TcNo Account Switcher - Tray start with logon';
$tasks = [];
$shouldExist = true;

if ($shouldExist && !isset($tasks[$task])) {
    $tasks[$task] = [
        'runLevel' => 'Highest',
        'triggers' => ['Logon'],
        'actions' => [realpath('TcNo-Acc-Switcher-Tray.exe') ?: 'TcNo-Acc-Switcher-Tray.exe'],
    ];
} elseif (!$shouldExist && isset($tasks[$task])) {
    unset($tasks[$task]);
}
?>
--EXPECT--
