--TEST--
Translated seed (inferredbugs-csharp: EasyData/1)
--FILE--
<?php

class MetaData {
    public $EntityRoot;

    public function __construct() {
        $this->EntityRoot = new stdClass();
        $this->EntityRoot->SubEntities = [];
    }

    public function LoadFromDbContext($dbContext) {
        // Simulate loading entities from a DbContext
        $this->EntityRoot->SubEntities = [
            (object) ["Name" => "Category", "Attributes" => array_fill(0, 4, null)],
            (object) ["Name" => "Customer", "Attributes" => array_fill(0, 11, null)],
            (object) ["Name" => "Employee", "Attributes" => array_fill(0, 19, null)],
            (object) ["Name" => "Order", "Attributes" => array_fill(0, 16, null)],
            (object) ["Name" => "Order Detail", "Attributes" => array_fill(0, 7, null)],
            (object) ["Name" => "Product", "Attributes" => array_fill(0, 12, null)],
            (object) ["Name" => "Shipper", "Attributes" => array_fill(0, 3, null)],
            (object) ["Name" => "Supplier", "Attributes" => array_fill(0, 12, null)],
        ];
    }
}

class TestDbContext {
    public static function Create() {
        // Simulate the creation of a DbContext
        return new stdClass();
    }
}

function main() {
    $dbContext = TestDbContext::Create();
    $meta = new MetaData();

    $meta->LoadFromDbContext($dbContext);

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

    foreach ($meta->EntityRoot->SubEntities as $entity) {
        // This simulates the original check for attribute counts
        $expectedCount = $entityAttrCount[$entity->Name];
        $actualCount = count($entity->Attributes);
        if ($actualCount !== $expectedCount) {
            exit(1);
        }
    }
}

main();
--EXPECT--
