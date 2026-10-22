--TEST--
Translated seed (inferredbugs-java: Nukkit/2)
--FILE--
<?php

class Entity {

    const DATA_AIR = 1;
    const DATA_FLAGS = 0;

    public static $entityCount = 1;

    protected $id;
    protected $dataFlags = 0;
    protected $fireTicks = 0;
    protected $health = 20;
    protected $isAlive = true;

    public function __construct() {
        $this->id = self::$entityCount++;
        $this->dataFlags = 0;
    }

    public function setHealth($health) {
        if ($health <= 0) {
            $this->isAlive = false;
            $this->health = 0;
        } else {
            $this->health = $health;
        }
    }

    public function getHealth() {
        return $this->health;
    }

    public function isAlive() {
        return $this->isAlive;
    }

    public function setOnFire($seconds) {
        $this->fireTicks = $seconds * 20;
    }

    public function entityBaseTick() {
        if (!$this->isAlive()) {
            return false;
        }

        if ($this->fireTicks > 0) {
            $this->health -= 1; // Damage from fire
            $this->fireTicks--;
            if ($this->health <= 0) {
                $this->isAlive = false;
            }
        }

        return true;
    }

    public function getDataFlag($propertyId, $id) {
        return ($this->dataFlags & (1 << $id)) > 0;
    }

    public function setDataFlag($propertyId, $id, $value) {
        if ($value) {
            $this->dataFlags |= (1 << $id);
        } else {
            $this->dataFlags &= ~(1 << $id);
        }
    }
}

$entity = new Entity();
$entity->setOnFire(3); // Set on fire for 3 seconds

for ($i = 0; $i < 61; $i++) { // Simulate 60 ticks
    $entity->entityBaseTick();
    if (!$entity->isAlive()) {
        break;
    }
}

echo $entity->getHealth(); // Output health after simulation
--EXPECT--
