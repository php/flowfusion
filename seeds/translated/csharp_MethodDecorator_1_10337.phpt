--TEST--
Translated seed (inferredbugs-csharp: MethodDecorator/1)
--FILE--
<?php
$xml = <<<XML
<Project>
  <PropertyGroup Condition="'$(Configuration)|$(Platform)' == 'Debug|AnyCPU'">
    <OutputPath>bin\Debug\</OutputPath>
  </PropertyGroup>
  <PropertyGroup>
    <AssemblyName>Example</AssemblyName>
  </PropertyGroup>
</Project>
XML;

$dom = new DOMDocument();
$dom->loadXML($xml);
$xp = new DOMXPath($dom);

$assemblyName = $xp->evaluate('string(//*[local-name()="AssemblyName"][1])');
$outputPath = $xp->evaluate('string(//*[local-name()="PropertyGroup" and normalize-space(@Condition)="\'$(Configuration)|$(Platform)\' == \'Debug|AnyCPU\'"]/*[local-name()="OutputPath"][1])');

if ($assemblyName === '' || $outputPath === '') {
    exit(0);
}

$base = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $assemblyName;
@mkdir($base . DIRECTORY_SEPARATOR . $outputPath, 0777, true);

$src = $base . DIRECTORY_SEPARATOR . $outputPath . $assemblyName . '.txt';
$dst = $base . DIRECTORY_SEPARATOR . $outputPath . $assemblyName . '2.txt';

file_put_contents($src, str_repeat("A", 1));
copy($src, $dst);
copy($src, str_replace('.txt', '.bak', $dst));

$fp = fopen($dst, 'r+');
flock($fp, LOCK_EX);
$data = stream_get_contents($fp);
fseek($fp, 0, SEEK_END);
fwrite($fp, $data . "B");
flock($fp, LOCK_UN);
fclose($fp);

echo filesize($dst) . PHP_EOL;
?>
--EXPECT--
