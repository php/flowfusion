--TEST--
Translated seed (inferredbugs-csharp: resin/55)
--FILE--
<?php
$dir = getcwd();
$path = $dir . DIRECTORY_SEPARATOR . 'sir.ini';
$config = is_file($path) ? parse_ini_file($path, false, INI_SCANNER_RAW) : [];
$dataDir = $config['data_dir'] ?? $dir;

$services = [];
$services['config'] = $config;
$services['model'] = [];
$services['dispatcher'] = ['logger' => 'Sir'];
$services['queryParser'] = ['directory' => $dataDir, 'sessionFactory' => $services['dispatcher'], 'model' => &$services['model']];
$services['httpParser'] = ['qp' => &$services['queryParser']];
$services['httpWriter'] = ['sessionFactory' => &$services['dispatcher'], 'config' => &$services['config']];
$services['httpReader'] = ['sessionFactory' => &$services['dispatcher'], 'httpParser' => &$services['httpParser'], 'config' => &$services['config'], 'logger' => 'HttpReader'];

echo "";
?>
--EXPECT--
