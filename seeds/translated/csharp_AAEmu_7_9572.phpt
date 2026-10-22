--TEST--
Translated seed (inferredbugs-csharp: AAEmu/7)
--FILE--
<?php

class HousingTemplate {
    public $Id;
    public $Name;
    public $Taxation;
    public $Hp;
    // other properties...
}

class House {
    public $Id;
    public $AccountId;
    public $OwnerId;
    public $Position;
    public $CurrentStep;
    // other properties...
}

class HousingManager {
    private $housingTemplates = [];
    private $houses = [];

    public function create($templateId) {
        if (!isset($this->housingTemplates[$templateId])) {
            return null;
        }

        $template = $this->housingTemplates[$templateId];
        $house = new House();
        $house->Id = $this->getNextId();
        $house->TemplateId = $template->Id;
        $house->AccountId = 1; // Simulated account ID
        $house->OwnerId = 1; // Simulated owner ID
        $house->CurrentStep = 0; // Simulated current step
        $this->houses[$house->Id] = $house;

        return $house;
    }

    private function getNextId() {
        return count($this->houses) + 1; // Simple ID generator
    }

    public function load() {
        $this->housingTemplates[1] = new HousingTemplate();
        $this->housingTemplates[1]->Id = 1;
        $this->housingTemplates[1]->Name = "Example House";
        $this->housingTemplates[1]->Taxation = null; // Simulated taxation
        $this->housingTemplates[1]->Hp = 100; // Simulated HP
    }

    public function save() {
        foreach ($this->houses as $house) {
            // Simulated save logic
            echo "Saving house ID: {$house->Id}\n";
        }
    }
}

function main() {
    $housingManager = new HousingManager();
    $housingManager->load();

    // Create a house to trigger the bug logic
    $house = $housingManager->create(1);
    if ($house) {
        echo "House created with ID: {$house->Id}\n";
    }

    $housingManager->save();
}

main();
--EXPECT--
