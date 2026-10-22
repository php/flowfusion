--TEST--
Translated seed (inferredbugs-java: android-maven-plugin/10)
--FILE--
<?php

$lintSkip = false;
$lintFailOnError = true;
$lintIgnoreWarnings = false;
$lintWarnAll = false;
$lintWarningsAsErrors = false;

$parsedFailOnError = false;
$parsedSkip = true;
$parsedIgnoreWarnings = false;
$parsedWarnAll = false;
$parsedWarningsAsErrors = false;

$projectDirectory = __DIR__;
$lintOutputPath = $projectDirectory . '/lint-results';

if ($parsedSkip) {
    echo "Skipping lint analysis.\n";
} else {
    echo "Performing lint analysis.\n";

    if ($parsedFailOnError) {
        echo "Lint analysis produced errors and project is configured to fail on error.\n";
    }

    $command = "php -r 'echo \"Lint analysis completed successfully.\";'";
    echo "Running command: $command\n";
    system($command);
}
--EXPECT--
