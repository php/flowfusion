--TEST--
Translated seed (inferredbugs-java: cassandra-lucene-index/8)
--FILE--
<?php

class Schema {
    private $analyzer;
    private $mappers;

    public function __construct($analyzer, $mappers) {
        $this->analyzer = $analyzer ?: new DefaultAnalyzer();
        $this->mappers = $mappers;
    }

    public function getDefaultAnalyzer() {
        return $this->analyzer;
    }

    public function getAnalyzer($name) {
        if (is_null($name) || trim($name) === '' || !isset($this->mappers[$name])) {
            throw new InvalidArgumentException();
        }
        return $this->mappers[$name];
    }
}

class DefaultAnalyzer {}

$mappers = [];
$schema = new Schema(new DefaultAnalyzer(), $mappers);
$analyzer = $schema->getDefaultAnalyzer();
$analyzer2 = $schema->getAnalyzer(""); 
$schema->getAnalyzer(null);
$schema->getAnalyzer("nonexistent");
--EXPECT--
