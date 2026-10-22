--TEST--
Translated seed (inferredbugs-csharp: resin/29)
--FILE--
<?php
class ConfigurationService {
    private array $data;
    function __construct(array $data) { $this->data = $data; }
    function get(string $key) { return $this->data[$key] ?? ''; }
}

class LatinTokenizer {}
class KeyValueBooleanQueryParser {}
class LocalStorageSessionFactory {
    function __construct($dataDir, $tokenizer, $config) {
        $this->dataDir = $dataDir;
        $this->tokenizer = $tokenizer;
        $this->config = $config;
    }
}
class HttpQueryParser {
    function __construct($parser) { $this->parser = $parser; }
}

$services = [];
$config = new ConfigurationService(['data_dir' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'data']);
$tokenizer = new LatinTokenizer();

$services[LocalStorageSessionFactory::class] = new LocalStorageSessionFactory(
    $config->get('data_dir'),
    $tokenizer,
    $config
);
$services['ITokenizer'] = $tokenizer;
$services[HttpQueryParser::class] = new HttpQueryParser(new KeyValueBooleanQueryParser());

echo "";
?>
--EXPECT--
