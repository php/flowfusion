--TEST--
Translated seed (inferredbugs-csharp: Win10BloatRemover/2)
--FILE--
<?php
$version = php_uname('r');
$min = '2009';
if (PHP_OS_FAMILY !== 'Windows' || version_compare($version, $min, '<')) {
    echo "-- UNSUPPORTED WINDOWS VERSION --\n\n";
    if (PHP_OS_FAMILY === 'Windows') {
        echo "You are running an older version of Windows which is not supported by this version of the program.\n";
        echo "You should update your system or download an older version of the program which is compatible with this\n";
        echo "Windows version ($version) at the following page:\n";
        echo "  https://github.com/\n";
    } else {
        echo "This program was designed to work only on Windows.\n";
    }
    echo "\nYou can still continue using this program, but BE AWARE that some features might work badly or not at all\n";
    echo "and could even have unintended effects on your system (including corruptions or instability).\n";
    echo "\nPress enter to continue, or another key to quit.\n";
    fgets(STDIN);
}
$config = [];
try {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'settings.json';
    if (is_file($file)) {
        $config = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    } else {
        $config = ['default' => true];
        file_put_contents($file, json_encode($config, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    }
} catch (Throwable $e) {
    echo "An error occurred while loading or writing settings file: " . $e->getMessage() . "\n";
    echo "Default settings have been loaded instead.\n";
    echo "Press a key to continue to the main menu.\n";
    fgets(STDIN);
    $config = ['default' => true];
}
$menu = [
    'system apps',
    'uwp apps',
    'edge',
    'onedrive',
    'services',
    'windows features',
    'privacy',
    'telemetry',
    'defender',
    'updates',
    'scheduled tasks',
    'error reporting',
    'suggestions',
    'github issue',
    'about',
    'quit'
];
echo implode("\n", $menu) . "\n";
?>
--EXPECT--
