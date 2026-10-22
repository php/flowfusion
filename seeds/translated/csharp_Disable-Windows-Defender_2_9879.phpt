--TEST--
Translated seed (inferredbugs-csharp: Disable-Windows-Defender/2)
--FILE--
<?php

function main() {
    $value = "1";
    $regPath1 = "SOFTWARE\\Policies\\Microsoft\\Windows Defender";
    $regPath2 = "SOFTWARE\\Policies\\Microsoft\\Windows Defender\\Real-Time Protection";

    registryEdit($regPath1, "DisableAntiSpyware", $value);
    registryEdit($regPath2, "DisableBehaviorMonitoring", $value);
    registryEdit($regPath2, "DisableOnAccessProtection", $value);
    registryEdit($regPath2, "DisableScanOnRealtimeEnable", $value);

    sleep(1);

    runPS("Set-MpPreference -DisableRealtimeMonitoring $true");
    runPS("Set-MpPreference -DisableBehaviorMonitoring $true");
    runPS("Set-MpPreference -DisableBlockAtFirstSeen $true");
    runPS("Set-MpPreference -DisableIOAVProtection $true");
    runPS("Set-MpPreference -DisablePrivacyMode $true");
    runPS("Set-MpPreference -SignatureDisableUpdateOnStartupWithoutEngine $true");
    runPS("Set-MpPreference -DisableArchiveScanning $true");
    runPS("Set-MpPreference -DisableScriptScanning $true");
    runPS("Set-MpPreference -SubmitSamplesConsent Never");
    runPS("Set-MpPreference -MAPSReporting 0");
}

function registryEdit($regPath, $name, $value) {
    // Simulate registry edit behavior
    try {
        // This is a placeholder for the actual registry editing logic
        // Since PHP does not have direct access to Windows Registry, we simulate it
        error_log("Registry Edit: $regPath, $name = $value");
    } catch (Exception $e) {
        error_log("Failed to edit registry: " . $e->getMessage());
    }
}

function runPS($args) {
    // Simulate PowerShell command execution
    try {
        // This is a placeholder for the actual PowerShell execution
        error_log("Running PowerShell command: $args");
    } catch (Exception $e) {
        error_log("Failed to run PowerShell command: " . $e->getMessage());
    }
}

main();
--EXPECT--
