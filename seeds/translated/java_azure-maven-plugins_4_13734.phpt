--TEST--
Translated seed (inferredbugs-java: azure-maven-plugins/4)
--FILE--
<?php

class AzureEnvironment {
    const AZURE = 'azure';
    const AZURE_GERMANY = 'azure_germany';
    const AZURE_CHINA = 'azure_china';
    const AZURE_US_GOVERNMENT = 'azure_us_government';
}

class AzureAuthHelper {
    public static function getAzureEnvironment($env) {
        if (is_null($env) || trim($env) === '') {
            return AzureEnvironment::AZURE;
        }
        return strtoupper($env) === 'AZURE_CLOUD' ? AzureEnvironment::AZURE : AzureEnvironment::AZURE_GERMANY;
    }

    public static function validateEnvironment($env) {
        return is_null($env) || trim($env) === '' || strtoupper($env) === 'AZURE_CLOUD';
    }
}

$tempDir = sys_get_temp_dir() . '/azure-auth-helper-test';
if (!file_exists($tempDir)) {
    mkdir($tempDir);
}

// Test getAzureEnvironment
$env1 = AzureAuthHelper::getAzureEnvironment(null);
$env2 = AzureAuthHelper::getAzureEnvironment(" ");
$env3 = AzureAuthHelper::getAzureEnvironment("azure");
$env4 = AzureAuthHelper::getAzureEnvironment("AZURE_CLOUD");

$validation1 = AzureAuthHelper::validateEnvironment(null);
$validation2 = AzureAuthHelper::validateEnvironment("");
$validation3 = AzureAuthHelper::validateEnvironment(" ");
$validation4 = AzureAuthHelper::validateEnvironment("foo");
--EXPECT--
