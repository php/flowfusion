--TEST--
Translated seed (inferredbugs-java: cassandra-lucene-index/1)
--FILE--
<?php

class ColumnMapper {}
class ColumnMapperInteger extends ColumnMapper {}
class ColumnMapperText extends ColumnMapper {}
class ColumnMapperBuilder {}
class ColumnMapperIntegerBuilder extends ColumnMapperBuilder {}
class ColumnMapperStringBuilder extends ColumnMapperBuilder {}
class Schema {
    private $columnMappers;
    private $defaultAnalyzer;

    public function __construct($columnMappers, $defaultAnalyzer) {
        $this->columnMappers = $columnMappers;
        $this->defaultAnalyzer = $defaultAnalyzer;
    }

    public function getDefaultAnalyzer() {
        return $this->defaultAnalyzer ?: 'default_analyzer';
    }

    public function getMapper($name) {
        return $this->columnMappers[$name] ?? null;
    }

    public function close() {}
}

$json = '{"analyzers":{},"default_analyzer":"default_analyzer","fields":{"id":{"type":"integer"},"spanish_text":{"type":"text","analyzer":"spanish_analyzer"},"snowball_text":{"type":"text","analyzer":"english_analyzer"}}}';
$data = json_decode($json, true);
$columnMappers = [
    'id' => new ColumnMapperIntegerBuilder(),
    'spanish_text' => new ColumnMapperText(),
    'snowball_text' => new ColumnMapperText()
];
$schema = new Schema($columnMappers, $data['default_analyzer']);

$defaultAnalyzer = $schema->getDefaultAnalyzer();
$idMapper = $schema->getMapper('id');
$spanishMapper = $schema->getMapper('spanish_text');
$snowballMapper = $schema->getMapper('snowball_text');

$schema->close();
--EXPECT--
