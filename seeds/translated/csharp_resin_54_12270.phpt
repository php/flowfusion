--TEST--
Translated seed (inferredbugs-csharp: resin/54)
--FILE--
<?php
$cfg = parse_ini_file(getcwd() . DIRECTORY_SEPARATOR . 'sir.ini', false, INI_SCANNER_RAW) ?: [];
$dataDir = $cfg['data_dir'] ?? getcwd();

$services = [];
$services['config'] = $cfg;
$services['loggerFactory'] = new class {
    public function createLogger(string $name) { return ['name' => $name]; }
};
$services['logger'] = $services['loggerFactory']->createLogger('Sir');
$services['model'] = new stdClass();
$services['database'] = new stdClass();
$services['queryParser'] = ['directory' => $dataDir, 'sessionFactory' => $services['database'], 'model' => $services['model'], 'logger' => $services['logger']];
$services['httpParser'] = ['qp' => $services['queryParser']];
$services['httpWriter'] = ['sessionFactory' => $services['database'], 'config' => $cfg];
$services['httpReader'] = ['sessionFactory' => $services['database'], 'httpParser' => $services['httpParser'], 'config' => $cfg, 'logger' => $services['loggerFactory']->createLogger('HttpReader')];

echo "";
?>
--EXPECT--
