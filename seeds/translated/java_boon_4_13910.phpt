--TEST--
Translated seed (inferredbugs-java: boon/4)
--FILE--
<?php

class Context {
    public $sortableFields = [];
}

function getSortableField($clazz) {
    global $context;
    
    if (!isset($context->sortableFields[$clazz])) {
        $fieldSortNames = ['name', 'orderBy', 'title', 'key'];
        $fieldSortNamesSuffixes = ['Name', 'Title', 'Key'];
        
        $fieldName = null;

        foreach ($fieldSortNames as $name) {
            if (property_exists($clazz, $name)) {
                $fieldName = $name;
                break;
            }
        }

        if ($fieldName === null) {
            foreach ($fieldSortNamesSuffixes as $suffix) {
                foreach (get_object_vars($clazz) as $key => $value) {
                    if (str_ends_with($key, $suffix) && is_string($value)) {
                        $fieldName = $key;
                        break 2;
                    }
                }
            }
        }

        if ($fieldName === null) {
            $properties = get_object_vars($clazz);
            foreach ($properties as $key => $value) {
                if (is_scalar($value)) {
                    $fieldName = $key;
                    break;
                }
            }
        }

        if ($fieldName === null) {
            $context->sortableFields[$clazz] = "NOT FOUND";
            die("Could not find a sortable field for type " . get_class($clazz));
        }

        $context->sortableFields[$clazz] = $fieldName;
    }

    return $context->sortableFields[$clazz];
}

$context = new Context();

class Example {
    public $name = "example";
    public $value;

    public function __construct($value) {
        $this->value = $value;
    }
}

$example = new Example("test");
echo getSortableField($example);
--EXPECT--
