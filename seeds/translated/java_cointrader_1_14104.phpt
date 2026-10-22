--TEST--
Translated seed (inferredbugs-java: cointrader/1)
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
        self::$lock = self::$lock ?? (object) [];
        $persisted = true;

        if (flock(fopen("lockfile", "w+"), LOCK_EX)) {
            try {
                foreach ($entities as $entity) {
                    self::persist($entity);
                }
            } catch (Exception $e) {
                $persisted = false;
            } finally {
                flock(fopen("lockfile", "w+"), LOCK_UN);
            }
        }

        foreach ($entities as $entity) {
            echo $persisted ? "{$entity->id} saved to database\n" : "{$entity->id} not saved to database\n";
        }
    }

    public static function merge(...$entities) {
        self::$lock = self::$lock ?? (object) [];
        $persisted = true;

        if (flock(fopen("lockfile", "w+"), LOCK_EX)) {
            try {
                foreach ($entities as $entity) {
                    self::mergeEntity($entity);
                }
            } catch (Exception $e) {
                $persisted = false;
            } finally {
                flock(fopen("lockfile", "w+"), LOCK_UN);
            }
        }

        foreach ($entities as $entity) {
            echo $persisted ? "{$entity->id} merged to database\n" : "{$entity->id} not merged to database\n";
        }
    }

    private static function persist($entity) {
        // Simulate database persist operation
    }

    private static function mergeEntity($entity) {
        // Simulate database merge operation
    }
}

$entity1 = new EntityBase(1);
$entity2 = new EntityBase(2);
PersistUtil::insert($entity1, $entity2);
PersistUtil::merge($entity1, $entity2);

?>
--EXPECT--
