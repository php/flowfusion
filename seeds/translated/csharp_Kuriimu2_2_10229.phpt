--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/2)
--FILE--
<?php
$manifestUrl = 'https://raw.githubusercontent.com/FanTranslatorsInternational/Kuriimu2-Update/master/Kuriimu2.Cmd/manifest.json';

echo "Welcome to Kuriimu2\n";
echo "\tAuthors: onepiecefreak, IcySon55, Neobeo, and other contributors\n";
echo "\tGithub link: https://github.com/FanTranslatorsInternational/Kuriimu2\n";

$localManifest = null;
$remoteManifest = @file_get_contents($manifestUrl);
if ($remoteManifest !== false) {
    $localManifest = json_decode('{"BuildNumber":0}', true);
    $remoteManifest = json_decode($remoteManifest, true);
    if (is_array($remoteManifest) && is_array($localManifest) && ($remoteManifest['BuildNumber'] ?? null) !== ($localManifest['BuildNumber'] ?? null)) {
        echo "\n";
        echo "A new version is available: " . $localManifest['BuildNumber'] . "\n";
    }
}

$loadErrors = [];
if ($loadErrors) {
    echo "\n";
    echo "Some plugins could not be loaded:\n";
    foreach ($loadErrors as $loadError) {
        echo "\t" . $loadError['AssemblyPath'] . " - " . $loadError['Exception']['Message'] . "\n";
    }
}

$pluginIds = [
    '11111111-1111-1111-1111-111111111111' => ['Name' => 'PluginA', 'Extensions' => ['bin', 'dat']],
    '22222222-2222-2222-2222-222222222222' => ['Name' => 'PluginB', 'Extensions' => ['rom']],
];

echo "No plugin could identify the file!\n";
echo "Select a plugin manually:\n";
foreach ($pluginIds as $id => $meta) {
    echo "[$id] - {$meta['Name']} | " . implode(';', $meta['Extensions']) . "\n";
}

$idArgument = $argv[1] ?? '';
if (!preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $idArgument)) {
    echo "'$idArgument' is not a valid plugin ID.\n";
    exit(0);
}

$result = $pluginIds[strtolower($idArgument)] ?? null;
?>
--EXPECT--
