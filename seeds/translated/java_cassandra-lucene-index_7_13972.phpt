--TEST--
Translated seed (inferredbugs-java: cassandra-lucene-index/7)
--FILE--
<?php

class Schema {
    private $analyzer;
    private $mappers;

    public function __construct($analyzer, $mappers) {
        $this->analyzer = $analyzer;
        $this->mappers = $mappers;
    }

    public function getDefaultAnalyzer() {
        return $this->analyzer ?? 'default_analyzer';
    }

    public function getAnalyzer($name) {
        if (is_null($name) || trim($name) === '') {
            throw new InvalidArgumentException();
        }
        if (!isset($this->mappers[$name])) {
            throw new InvalidArgumentException();
        }
        return $this->mappers[$name];
    }

    public function close() {}

    public function validate($metadata) {}
}

function stringMapper() { return 'string_mapper'; }
function textMapper() { return 'text_mapper'; }

$schema1 = new Schema('english_analyzer', []);
$analyzer1 = $schema1->getDefaultAnalyzer();
$schema1->close();

$schema2 = new Schema(null, []);
$analyzer2 = $schema2->getDefaultAnalyzer();
$schema2->close();

$schema3 = new Schema('english_analyzer', []);
try { $schema3->getAnalyzer("custom"); } catch (InvalidArgumentException $e) {}
$schema3->close();

$schema4 = new Schema('english_analyzer', []);
try { $schema4->getAnalyzer(null); } catch (InvalidArgumentException $e) {}
$schema4->close();

$schema5 = new Schema('english_analyzer', []);
try { $schema5->getAnalyzer(" "); } catch (InvalidArgumentException $e) {}
$schema5->close();

$metadata = 'metadata'; // Placeholder for actual metadata handling
$schema6 = new Schema('default_analyzer', ['field1' => stringMapper(), 'field2' => textMapper()]);
$schema6->validate($metadata);
$schema6->close();

$schema7 = new Schema('default_analyzer', ['field1' => stringMapper(), 'field2' => textMapper()]);
$schema7->toString();
$schema7->close();
--EXPECT--
