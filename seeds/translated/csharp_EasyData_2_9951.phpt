--TEST--
Translated seed (inferredbugs-csharp: EasyData/2)
--FILE--
<?php

class Entity {
    public $name;
    public $attributes;

    public function __construct($name, $attributes) {
        $this->name = $name;
        $this->attributes = $attributes;
    }
}

class MetaData {
    public $entityRoot;

    public function __construct() {
        $this->entityRoot = new stdClass();
        $this->entityRoot->subEntities = [];
    }

    public function loadFromDbContext($dbContext) {
        // Simulated loading from a DB context
        $this->entityRoot->subEntities = [
            new Entity("Category", array_fill(0, 4, '')),
            new Entity("Customer", array_fill(0, 11, '')),
            new Entity("Employee", array_fill(0, 19, '')),
            new Entity("Order", array_fill(0, 16, '')),
            new Entity("Order Detail", array_fill(0, 7, '')),
            new Entity("Product", array_fill(0, 12, '')),
            new Entity("Shipper", array_fill(0, 3, '')),
            new Entity("Supplier", array_fill(0, 12, ''))
        ];
    }
}

$dbContext = new stdClass(); // Simulating a DbContext
$meta = new MetaData();

$meta->loadFromDbContext($dbContext);

$entityAttrCount = [
    "Category" => 4,
    "Customer" => 11,
    "Employee" => 19,
    "Order" => 16,
    "Order Detail" => 7,
    "Product" => 12,
    "Shipper" => 3,
    "Supplier" => 12
];

foreach ($meta->entityRoot->subEntities as $entity) {
    $count = count($entity->attributes);
    if ($count !== $entityAttrCount[$entity->name]) {
        // This simulates a potential bug if the counts don't match
        echo "Bug detected for entity: " . $entity->name . "\n";
    }
}
--EXPECT--
