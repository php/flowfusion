--TEST--
Translated seed (inferredbugs-java: cassandra-lucene-index/2)
--FILE--
<?php

class Schema {
    private $analyzers;
    private $defaultAnalyzer;

    public function __construct(array $analyzers, $defaultAnalyzer) {
        $this->analyzers = $analyzers;
        $this->defaultAnalyzer = $defaultAnalyzer;
    }

    public function getDefaultAnalyzer() {
        return $this->defaultAnalyzer ?? 'default_analyzer';
    }

    public function getAnalyzer($name) {
        if (empty($name) || !isset($this->analyzers[$name])) {
            throw new InvalidArgumentException();
        }
        return $this->analyzers[$name];
    }
}

$json = '{"analyzers":{}, "default_analyzer": null}';
$data = json_decode($json, true);
$schema = new Schema($data['analyzers'], $data['default_analyzer']);

$analyzer = $schema->getDefaultAnalyzer();

try {
    $schema->getAnalyzer('non_existent');
} catch (InvalidArgumentException $e) {
}

try {
    $schema->getAnalyzer(null);
} catch (InvalidArgumentException $e) {
}

try {
    $schema->getAnalyzer('');
} catch (InvalidArgumentException $e) {
}
--EXPECT--
