--TEST--
Translated seed (inferredbugs-csharp: TcNo-Acc-Switcher/5)
--FILE--
<?php
$task = 'TcNo Account Switcher - Tray start with logon';
$tasks = [];
$shouldExist = (bool)random_int(0, 1);

if ($shouldExist) {
    if (!isset($tasks[$task])) {
        $tasks[$task] = [
            'runLevel' => 'Highest',
            'trigger' => 'Logon',
            'action' => getcwd() . DIRECTORY_SEPARATOR . 'TcNo-Acc-Switcher-Tray.exe',
        ];
    }
} else {
    if (isset($tasks[$task])) {
        unset($tasks[$task]);
    }
}

echo isset($tasks[$task]) ? "1" : "0";
?>
--EXPECT--
