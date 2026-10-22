--TEST--
Translated seed (inferredbugs-java: SimpleFlatMapper/2)
--FILE--
<?php

class ResultSetMapperBuilder {
    private $fields = [];
    
    public function addMapping($property, $column) {
        $type = gettype($property);
        if (is_int($property)) {
            $this->fields[] = $this->primitiveFieldMapper($column, $type);
        } else {
            $this->fields[] = $this->objectFieldMapper($column, $type);
        }
        return $this;
    }
    
    private function objectFieldMapper($column, $type) {
        if (!class_exists($type)) {
            throw new InvalidArgumentException("No getter for column $column type $type");
        }
        // Simulating object mapping
        return "ObjectFieldMapper for $column of type $type";
    }

    private function primitiveFieldMapper($column, $type) {
        // Simulating primitive mapping
        switch ($type) {
            case 'boolean':
                return "BooleanFieldMapper for $column";
            case 'integer':
                return "IntFieldMapper for $column";
            case 'double':
                return "DoubleFieldMapper for $column";
            case 'string':
                return "StringFieldMapper for $column";
            default:
                throw new BadMethodCallException("Type $type is not primitive");
        }
    }
}

$builder = new ResultSetMapperBuilder();
$builder->addMapping('testProperty', 'column1');
$builder->addMapping(5, 'column2');
--EXPECT--
