--TEST--
Translated seed (inferredbugs-csharp: SharpEDRChecker/1)
--FILE--
<?php
$drivers = [
    ['Name' => 'WinDefend', 'DisplayName' => 'Microsoft Defender Antivirus Service', 'Description' => 'Protects against malware', 'Caption' => 'Windows Defender', 'PathName' => 'C:\\Windows\\System32\\MsMpEng.exe', 'State' => 'Running', 'StartName' => 'LocalSystem'],
    ['Name' => 'spooler', 'DisplayName' => 'Print Spooler', 'Description' => 'Loads files to memory', 'Caption' => 'Print Spooler', 'PathName' => 'C:\\Windows\\System32\\spoolsv.exe', 'State' => 'Running', 'StartName' => 'LocalSystem'],
    ['Name' => 'kbdclass', 'DisplayName' => 'Keyboard Class Driver', 'Description' => 'Keyboard input driver', 'Caption' => 'Keyboard Driver', 'PathName' => 'C:\\Windows\\System32\\drivers\\kbdclass.sys', 'State' => 'Stopped', 'StartName' => 'LocalSystem'],
];

$edrlist = ['defender', 'crowdstrike', 'sentinel', 'carbon black', 'msmpeng', 'edr'];

$found = false;
foreach ($drivers as $driver) {
    $all = strtolower(($driver['Name'] ?? '') . ' - ' . ($driver['DisplayName'] ?? '') . ' - ' . ($driver['Description'] ?? '') . ' - ' . ($driver['Caption'] ?? '') . ' - ' . ($driver['PathName'] ?? ''));
    foreach ($edrlist as $needle) {
        if (str_contains($all, strtolower($needle))) {
            $found = true;
            break;
        }
    }
}

if (!$found) {
    echo "[+] No suspicious drivers found\n";
}
?>
--EXPECT--
