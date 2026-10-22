--TEST--
Translated seed (inferredbugs-java: Metanome/3)
--FILE--
<?php

class ColumnIdentifier {
    private $table;
    private $column;

    public function __construct($table, $column) {
        $this->table = $table;
        $this->column = $column;
    }

    public function __toString() {
        return "{$this->table}.{$this->column}";
    }
}

class ColumnCombination {
    private $columns;

    public function __construct(ColumnIdentifier ...$columns) {
        $this->columns = $columns;
    }

    public function __toString() {
        return implode(', ', $this->columns);
    }
}

class ConfigurationValueString {
    private $key;
    private $value;

    public function __construct($key, $value) {
        $this->key = $key;
        $this->value = $value;
    }
}

class AlgorithmExecuter {
    public function executeUniqueColumnCombinationsAlgorithm($jar, $configs, $printer) {
        // Simulate the execution and output
        $output = (string) new ColumnCombination(
            new ColumnIdentifier("table1", "column1"),
            new ColumnIdentifier("table2", "column2")
        );
        $printer->print($output);
    }
}

class UniqueColumnCombinationPrinter {
    private $stream;

    public function __construct($stream) {
        $this->stream = $stream;
    }

    public function print($value) {
        fwrite($this->stream, $value);
    }
}

$outStream = fopen('php://memory', 'r+');
$configs = [new ConfigurationValueString("pathToInputFile", "blub")];
$executer = new AlgorithmExecuter();
$printer = new UniqueColumnCombinationPrinter($outStream);

$executer->executeUniqueColumnCombinationsAlgorithm("example_algorithm-0.0.1-SNAPSHOT-jar-with-dependencies.jar", $configs, $printer);

rewind($outStream);
$output = stream_get_contents($outStream);
fclose($outStream);

if (strpos($output, (string)new ColumnCombination(new ColumnIdentifier("table1", "column1"), new ColumnIdentifier("table2", "column2"))) !== false) {
    exit(0);
}
exit(1);
--EXPECT--
