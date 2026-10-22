--TEST--
Translated seed (inferredbugs-csharp: resin/53)
--FILE--
<?php
$dir = getcwd();
$path = $dir . DIRECTORY_SEPARATOR . 'sir.ini';
$config = [];
if (is_file($path)) {
    $config = parse_ini_file($path, false, INI_SCANNER_RAW) ?: [];
}
$dataDir = $config['data_dir'] ?? $dir;

$services = [];
$services['config'] = $config;
$services['logger_factory'] = new stdClass();
$services['logger'] = 'Sir';
$services['model'] = [];
$services['stream_factory'] = ['logger' => $services['logger']];
$services['query_parser'] = ['directory' => $dataDir, 'session_factory' => $services['stream_factory'], 'model' => $services['model'], 'logger' => $services['logger']];
$services['http_parser'] = ['qp' => $services['query_parser']];
$services['http_writer'] = ['session_factory' => $services['stream_factory'], 'config' => $config];
$services['http_reader'] = ['session_factory' => $services['stream_factory'], 'http_parser' => $services['http_parser'], 'config' => $config, 'logger' => $services['logger']];

echo "";
?>
--EXPECT--
