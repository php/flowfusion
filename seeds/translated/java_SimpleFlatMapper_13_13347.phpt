--TEST--
Translated seed (inferredbugs-java: SimpleFlatMapper/13)
--FILE--
<?php

class AbstractFieldMapperMapperBuilder {

    protected $source;
    protected $target;
    protected $additionalMappers = [];
    protected $propertyMappingsBuilder;

    public function __construct($source, $target) {
        if ($source === null) {
            throw new Exception("source is null");
        }
        if ($target === null) {
            throw new Exception("target is null");
        }
        $this->source = $source;
        $this->target = $target;
        $this->propertyMappingsBuilder = [];
    }

    protected function addMapping($key, $definition) {
        $this->propertyMappingsBuilder[$key] = $definition;
    }

    public function fields() {
        $fields = [];
        foreach ($this->propertyMappingsBuilder as $mapping) {
            if ($mapping !== null) {
                $fields[] = $this->newFieldMapper($mapping);
            }
        }
        return $fields;
    }

    protected function newFieldMapper($mapping) {
        if (isset($mapping['customFieldMapper'])) {
            return $mapping['customFieldMapper'];
        } else {
            return $this->createFieldMapper($mapping);
        }
    }

    protected function createFieldMapper($mapping) {
        return "FieldMapper for " . $mapping['key'];
    }

    public function run() {
        $this->addMapping('column1', ['key' => 'column1']);
        $this->addMapping('column2', ['key' => 'column2', 'customFieldMapper' => 'CustomFieldMapper']);
        $fields = $this->fields();
        var_dump($fields);
    }
}

$builder = new AbstractFieldMapperMapperBuilder("sourceType", "targetType");
$builder->run();
--EXPECT--
