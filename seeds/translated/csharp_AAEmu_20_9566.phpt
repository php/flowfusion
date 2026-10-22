--TEST--
Translated seed (inferredbugs-csharp: AAEmu/20)
--FILE--
<?php

class House {
    public $Id;
    public $OwnerId = 0;
    public $CoOwnerId = 0;
    public $ProtectionEndDate;
    public $IsDirty = false;

    public function __construct($id) {
        $this->Id = $id;
        $this->ProtectionEndDate = new DateTime();
    }

    public function demolish() {
        $this->IsDirty = true;
        // Additional cleanup, like notifying owners, could be done here.
    }
}

class HousingManager {
    private $houses = [];
    private $removedHousings = [];

    public function addHouse($house) {
        $this->houses[$house->Id] = $house;
    }

    public function checkHousingTaxes() {
        foreach ($this->houses as $house) {
            if ($house->ProtectionEndDate <= new DateTime() && $house->OwnerId > 0) {
                $this->demolish($house);
            }
        }
    }

    private function demolish($house) {
        // Mark for removal
        $house->demolish();
        $this->removedHousings[] = $house->Id;
        unset($this->houses[$house->Id]);
    }
}

$housingManager = new HousingManager();

// Simulate creation of houses
for ($i = 1; $i <= 5; $i++) {
    $house = new House($i);
    // Set protection end date to past to trigger demolition
    $house->ProtectionEndDate->modify('-1 day');
    $housingManager->addHouse($house);
}

// Check for houses to demolish
$housingManager->checkHousingTaxes();
--EXPECT--
