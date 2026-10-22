--TEST--
Translated seed (inferredbugs-csharp: AAEmu/17)
--FILE--
<?php

class House {
    public $id;
    public $accountId;
    public $ownerId;
    public $name;
    public $protectionEndDate;

    public function __construct($id, $accountId, $ownerId, $name) {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->ownerId = $ownerId;
        $this->name = $name;
        $this->protectionEndDate = new DateTimeImmutable('+2 weeks');
    }

    public function demolish() {
        $this->ownerId = null;
        $this->accountId = null;
        $this->protectionEndDate = new DateTimeImmutable('-1 day');
    }
}

class HousingManager {
    private $houses = [];
    private $removedHousings = [];

    public function addHouse(House $house) {
        $this->houses[$house->id] = $house;
    }

    public function checkHousingTaxes() {
        foreach ($this->houses as $house) {
            if ($house->protectionEndDate <= new DateTimeImmutable()) {
                $this->demolish($house);
            }
        }
    }

    private function demolish(House $house) {
        $house->demolish();
        $this->removedHousings[] = $house->id;
        unset($this->houses[$house->id]);
    }
}

$housingManager = new HousingManager();
$housingManager->addHouse(new House(1, 1001, 2001, 'My First House'));
$housingManager->addHouse(new House(2, 1002, 2002, 'My Second House'));

sleep(2); // simulate passage of time for demonstration

$housingManager->checkHousingTaxes();

echo "Houses left after tax check: " . count($housingManager->houses) . "\n";
--EXPECT--
