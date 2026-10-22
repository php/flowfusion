--TEST--
Translated seed (inferredbugs-java: azure-maven-plugins/3)
--FILE--
<?php

class AzureEnvironment {
    const AZURE = 'azure';
    const AZURE_GERMANY = 'azure_germany';
    const AZURE_US_GOVERNMENT = 'azure_us_government';
    const AZURE_CHINA = 'azure_china';
}

class AzureAuthHelper {
    public static function getAzureEnvironment($env) {
        $validEnvs = [
            null => AzureEnvironment::AZURE,
            '' => AzureEnvironment::AZURE,
            ' ' => AzureEnvironment::AZURE,
            'azure' => AzureEnvironment::AZURE,
            'AZURE_CLOUD' => AzureEnvironment::AZURE,
            'AZURE_GERMANY' => AzureEnvironment::AZURE_GERMANY,
            'AzureGermanCloud' => AzureEnvironment::AZURE_GERMANY,
            'AZURE_US_GOVERNMENT' => AzureEnvironment::AZURE_US_GOVERNMENT,
            'AzureChinaCloud' => AzureEnvironment::AZURE_CHINA,
        ];
        return $validEnvs[strtolower(trim($env))] ?? AzureEnvironment::AZURE;
    }

    public static function validateEnvironment($env) {
        return in_array(trim($env), [
            '',
            ' ',
            'azure',
            'azure_cloud',
            'AZURE_CLOUD',
            'aZURe',
        ]);
    }
}

$env1 = AzureAuthHelper::getAzureEnvironment(null);
$env2 = AzureAuthHelper::getAzureEnvironment("  ");
$env3 = AzureAuthHelper::getAzureEnvironment("azure");
$env4 = AzureAuthHelper::getAzureEnvironment("AZURE_CLOUD");
$env5 = AzureAuthHelper::getAzureEnvironment("unknown");

$valid1 = AzureAuthHelper::validateEnvironment(null);
$valid2 = AzureAuthHelper::validateEnvironment(" ");
$valid3 = AzureAuthHelper::validateEnvironment("foo");
--EXPECT--
