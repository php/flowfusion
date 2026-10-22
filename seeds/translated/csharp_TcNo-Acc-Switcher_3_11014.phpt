--TEST--
Translated seed (inferredbugs-csharp: TcNo-Acc-Switcher/3)
--FILE--
<?php
$k = "TcNo Account Switcher - Tray start with logon";
$a = [];
$e = array_key_exists($k, $a);
if (!$e) {
    $a[$k] = ["runlevel" => "highest", "trigger" => "logon", "action" => realpath("TcNo-Acc-Switcher-Tray.exe") ?: "TcNo-Acc-Switcher-Tray.exe"];
}
$e = array_key_exists($k, $a);
if ($e) {
    unset($a[$k]);
}
?>
--EXPECT--
