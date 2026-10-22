--TEST--
Translated seed (inferredbugs-csharp: AAEmu/16)
--FILE--
<?php

class House {
    public $id;
    public $templateId;
    public $ownerId;
    public $protectionEndDate;
    public $name;
    
    public function __construct($id, $templateId, $ownerId, $name) {
        $this->id = $id;
        $this->templateId = $templateId;
        $this->ownerId = $ownerId;
        $this->name = $name;
        $this->protectionEndDate = new DateTime('+7 days');
    }
}

class HousingManager {
    private $houses = [];
    private $removedHousings = [];

    public function createHouse($id, $templateId, $ownerId, $name) {
        $house = new House($id, $templateId, $ownerId, $name);
        $this->houses[$id] = $house;
        return $house;
    }

    public function demolishHouse($houseId) {
        if (isset($this->houses[$houseId])) {
            $house = $this->houses[$houseId];

            // Check if house is eligible for demolition
            if ($house->protectionEndDate <= new DateTime()) {
                $this->removedHousings[] = $houseId;
                unset($this->houses[$houseId]);
            }
        }
    }
    
    public function checkHouses() {
        foreach ($this->houses as $house) {
            if ($house->protectionEndDate <= new DateTime()) {
                $this->demolishHouse($house->id);
            }
        }
    }

    public function getHouseById($houseId) {
        return $this->houses[$houseId] ?? null;
    }
}

function main() {
    $housingManager = new HousingManager();
    
    // Create houses
    $housingManager->createHouse(1, 101, 1, 'My First House');
    $housingManager->createHouse(2, 102, 1, 'My Second House');
    
    // Simulate time passing for protection expiration
    sleep(8); // Assume 8 seconds simulates time passing
    
    // Check houses for demolition
    $housingManager->checkHouses();
    
    // Check remaining houses
    print_r($housingManager);
}

main();
--EXPECT--
