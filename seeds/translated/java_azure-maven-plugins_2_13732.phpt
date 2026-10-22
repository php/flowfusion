--TEST--
Translated seed (inferredbugs-java: azure-maven-plugins/2)
--FILE--
<?php

class AzureEnvironment {
    const AZURE = 'Azure';
    const AZURE_CHINA = 'AzureChinaCloud';
    const AZURE_GERMANY = 'AzureGermany';
    const AZURE_US_GOVERNMENT = 'AzureUSGovernment';
}

function refreshToken($env, $token) {
    if ($env === null) {
        throw new InvalidArgumentException('Environment cannot be null.');
    }
    if (empty($token)) {
        throw new InvalidArgumentException('Token cannot be empty.');
    }
    // Simulated behavior for valid token
    if ($token === 'invalid') {
        throw new Exception('Invalid refresh token.');
    }
    return 'valid_token';
}

$tempDirectory = sys_get_temp_dir() . '/azure-auth-helper-test';
mkdir($tempDirectory);

try {
    $env = AzureEnvironment::AZURE;
    $validToken = refreshToken($env, 'token for power mock');

    // Triggering invalid token exception
    try {
        refreshToken($env, 'invalid');
    } catch (Exception $e) {}
    
    // Triggering invalid parameter exception
    try {
        refreshToken(null, 'abc');
    } catch (InvalidArgumentException $e) {}
    
    try {
        refreshToken(AzureEnvironment::AZURE_CHINA, '');
    } catch (InvalidArgumentException $e) {}
    
    // Simulate normal operation with a valid token
    $result = refreshToken($env, 'valid_token');

} finally {
    rmdir($tempDirectory);
}
--EXPECT--
