--TEST--
Translated seed (inferredbugs-java: android-maven-plugin/1)
--FILE--
<?php

$androidVersion = "0.9_beta";
$localRepository = "/path/to/local/repo"; // Adjust path as necessary
$resourceDirectory = "/path/to/resources"; // Adjust path as necessary
$androidManifestFile = $resourceDirectory . "/AndroidManifest.xml";

$tmpOutputFile = tempnam(sys_get_temp_dir(), "android") . ".apk";
register_shutdown_function(function() use ($tmpOutputFile) { unlink($tmpOutputFile); });

$androidJar = $localRepository . "/android/android/" . $androidVersion . "/android-" . $androidVersion . ".jar";

$outputFile = "/path/to/output/" . "artifact-id-version.apk"; // Adjust path as necessary

$commands = [
    "package",
    "-f",
    "-M",
    $androidManifestFile,
];
if (is_dir($resourceDirectory)) {
    $commands[] = "-S";
    $commands[] = $resourceDirectory;
}
$commands[] = "-I";
$commands[] = $androidJar;
$commands[] = "-F";
$commands[] = $tmpOutputFile;

exec("aapt " . implode(" ", $commands), $output, $return_var);

$dexClassesFile = "/path/to/classes.dex"; // Adjust path as necessary

$zip = new ZipArchive();
$zip->open($outputFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

$zip->open($tmpOutputFile);
for ($i = 0; $i < $zip->numFiles; $i++) {
    $zip->addFile($zip->getNameIndex($i), $zip->getNameIndex($i));
}
$zip->addFile($dexClassesFile, "classes.dex");

$zip->close();
--EXPECT--
