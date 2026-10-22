--TEST--
Translated seed (inferredbugs-java: Metanome/13)
--FILE--
<?php

class CsvFileGenerator {
    private $fileName;
    function __construct($fileName) {
        if (!file_exists($fileName)) {
            throw new Exception("Could not find specified CSV file.");
        }
        $this->fileName = $fileName;
    }
}

class ConfigurationValue {
    public $identifier;
    public function __construct($identifier) {
        $this->identifier = $identifier;
    }
}

class ConfigurationValueString extends ConfigurationValue {
    public $value;
    public function __construct($identifier, $value) {
        parent::__construct($identifier);
        $this->value = $value;
    }
}

class InputParameter {
    public $identifier;
    function __construct($identifier) {
        $this->identifier = $identifier;
    }
}

class InputParameterString extends InputParameter {
    public $value;
    function __construct($identifier, $value) {
        parent::__construct($identifier);
        $this->value = $value;
    }
}

class AlgorithmExecutor {
    function executeAlgorithm($name, $configs) {}
}

$parameters[] = new InputParameterString("param1", "value1");
$configs = [];
foreach ($parameters as $parameter) {
    if ($parameter instanceof InputParameterString) {
        $configs[] = new ConfigurationValueString($parameter->identifier, $parameter->value);
    }
}

$fileGenerator = new CsvFileGenerator("example.csv");
$executor = new AlgorithmExecutor();
$executor->executeAlgorithm("ExampleAlgorithm", $configs);
--EXPECT--
