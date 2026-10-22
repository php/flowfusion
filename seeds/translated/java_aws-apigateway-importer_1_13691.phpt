--TEST--
Translated seed (inferredbugs-java: aws-apigateway-importer/1)
--FILE--
<?php

$region = "us-east-1";
$profile = "default";
$home = getenv("HOME");
$awsConfigFile = $home . "/.aws/config";

$awsConfigProperties = parse_ini_file($awsConfigFile);

if ($awsConfigProperties !== false) {
    if (isset($awsConfigProperties['region'])) {
        $region = $awsConfigProperties['region'];
    } else {
        echo "Could not load region configuration. Please ensure AWS CLI is configured via 'aws configure'. Will use default region of $region\n";
    }
}
?>
--EXPECT--
