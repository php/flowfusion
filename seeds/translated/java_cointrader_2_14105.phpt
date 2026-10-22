--TEST--
Translated seed (inferredbugs-java: cointrader/2)
--FILE--
<?php

class EntityBase {
    public $id;
    public function __construct($id) {
        $this->id = $id;
    }
}

class PersistUtil {
    private static $lock;

    public static function insert(...$entities) {
        if (self::$lock === null) {
            self::$lock = new stdClass();
        }
        
        $persisted = true;
        try {
            foreach ($entities as $entity) {
                // Simulate a persist operation
                echo get_class($entity) . ": " . $entity->id . " saved to database\n";
            }
        } catch (Exception $e) {
            $persisted = false;
            echo "Error: " . $e->getMessage() . "\n";
        } finally {
            if (!$persisted) {
                foreach ($entities as $entity) {
                    echo get_class($entity) . ": " . $entity->id . " not saved to database\n";
                }
            }
        }
    }

    public static function merge(...$entities) {
        $persisted = true;
        try {
            foreach ($entities as $entity) {
                // Simulate a merge operation
                echo get_class($entity) . ": " . $entity->id . " merged to database\n";
            }
        } catch (Exception $e) {
            $persisted = false;
            echo "Error: " . $e->getMessage() . "\n";
        } finally {
            if (!$persisted) {
                foreach ($entities as $entity) {
                    echo get_class($entity) . ": " . $entity->id . " not saved to database\n";
                }
            }
        }
    }
}

$entity1 = new EntityBase('1');
$entity2 = new EntityBase('2');
PersistUtil::insert($entity1, $entity2);
PersistUtil::merge($entity1, $entity2);
--EXPECT--
