--TEST--
Translated seed (inferredbugs-csharp: resin/50)
--FILE--
<?php
$dir = getcwd();
$config = parse_ini_file($dir . DIRECTORY_SEPARATOR . 'sir.ini', true, INI_SCANNER_RAW);

$services = [];
$services['config'] = $config;
$services['loggerFactory'] = new class {
    public function createLogger(string $name) { return $name; }
};
$services['model'] = new stdClass();
$services['sessionFactory'] = ['root' => 'c:\\data\\resin', 'logger' => $services['loggerFactory']->createLogger('Sir')];
$services['qp'] = ['sessionFactory' => $services['sessionFactory'], 'model' => $services['model'], 'logger' => $services['loggerFactory']->createLogger('Sir')];
$services['httpParser'] = ['qp' => $services['qp']];
$services['httpWriter'] = ['sessionFactory' => $services['sessionFactory']];
$services['httpReader'] = ['sessionFactory' => $services['sessionFactory'], 'httpParser' => $services['httpParser'], 'logger' => $services['loggerFactory']->createLogger('HttpReader')];

echo '';
?>
--EXPECT--
