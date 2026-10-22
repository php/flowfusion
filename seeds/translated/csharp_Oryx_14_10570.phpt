--TEST--
Translated seed (inferredbugs-csharp: Oryx/14)
--FILE--
<?php
$projectFile = 'project.csproj';
$file = '<?xml version="1.0" encoding="utf-8"?><Project><PropertyGroup><TargetFramework>netcoreapp2.2</TargetFramework></PropertyGroup></Project>';
$xml = simplexml_load_string($file);
$targetFramework = (string)$xml->PropertyGroup->TargetFramework;
$targetFramework = strtolower(str_replace('netcoreapp', '', $targetFramework));
$version = is_numeric($targetFramework) ? (string)(0 + $targetFramework) : null;
if ($version !== null) {
    echo $version;
}
?>
--EXPECT--
