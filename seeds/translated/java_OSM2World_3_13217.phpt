--TEST--
Translated seed (inferredbugs-java: OSM2World/3)
--FILE--
<?php

class CLIArguments {
    public $config = null;
    public $mode = 'GUI';
}

class GlobalValues {
    const VERSION_STRING = '1.0.0';
}

class ProgramMode {
    const HELP = 'HELP';
    const VERSION = 'VERSION';
    const GUI = 'GUI';
    const CONVERT = 'CONVERT';
}

function parseArguments($args) {
    $cliArgs = new CLIArguments();
    foreach ($args as $arg) {
        if (strpos($arg, '--config=') === 0) {
            $cliArgs->config = substr($arg, 9);
        } elseif ($arg === '--help') {
            $cliArgs->mode = ProgramMode::HELP;
        } elseif ($arg === '--version') {
            $cliArgs->mode = ProgramMode::VERSION;
        } elseif ($arg === '--convert') {
            $cliArgs->mode = ProgramMode::CONVERT;
        }
    }
    return $cliArgs;
}

$unparsedArgs = $argv;
$programMode = ProgramMode::GUI;
$args = null;

if (count($unparsedArgs) > 1) {
    $args = parseArguments(array_slice($unparsedArgs, 1));
    $programMode = $args->mode;
}

if ($programMode === ProgramMode::HELP) {
    echo "Usage: php script.php [options]\n";
    echo "Options:\n";
    echo "  --help        Show help\n";
    echo "  --version     Show version\n";
    echo "  --config=FILE Load configuration from FILE\n";
    echo "  --convert     Convert data\n";
    exit(0);
}

if ($programMode === ProgramMode::VERSION) {
    echo "OSM2World " . GlobalValues::VERSION_STRING . "\n";
    exit(0);
}

$config = [];

if ($args && $args->config) {
    if (file_exists($args->config)) {
        $config = parse_ini_file($args->config);
    } else {
        echo "Could not read config, ignoring it.\n";
    }
}

if ($programMode === ProgramMode::CONVERT) {
    // Simulate data conversion with the loaded config
    echo "Data conversion with config: ";
    print_r($config);
}

?>
--EXPECT--
