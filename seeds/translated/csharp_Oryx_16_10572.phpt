--TEST--
Translated seed (inferredbugs-csharp: Oryx/16)
--FILE--
<?php
$xml = '<Project><PropertyGroup><TargetFramework>netcoreapp2.2</TargetFramework><OutputType>Exe</OutputType></PropertyGroup></Project>';
$doc = new DOMDocument();
$doc->loadXML($xml);
$xp = new DOMXPath($doc);

$tf = $xp->evaluate('string(/Project/PropertyGroup/TargetFramework)');
if ($tf === '') {
    exit(0);
}

$ot = $xp->evaluate('string(/Project/PropertyGroup/OutputType)');
$ot = $ot === '' ? 'Library' : $ot;

$version = strtolower($tf);
$version = str_replace('netcoreapp', '', $version);
$version = str_replace('net', '', $version);

if (is_numeric($version)) {
    $version = (string)(0 + $version);
} else {
    $version = null;
}

$result = [
    'Platform' => 'DotNetCore',
    'PlatformVersion' => $version,
    'ProjectFile' => 'project.csproj',
    'AppDirectory' => '.',
    'OutputType' => $ot,
];

echo $result['PlatformVersion'] . "\n";
?>
--EXPECT--
