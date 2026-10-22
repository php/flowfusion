--TEST--
Translated seed (inferredbugs-csharp: AAEmu/9)
--FILE--
<?php

class House {
    public $Id;
    public $AccountId;
    public $OwnerId;
    public $Position;
    public $CurrentStep;
    public $NumAction;
    public $Permission;
}

class HousingTemplate {
    public $Id;
    public $Name;
    public $Taxation;
}

class Database {
    public static function createConnection() {
        // Simulating database connection
        return new stdClass();
    }
}

class HousingManager {
    private $housingTemplates = [];
    private $houses = [];

    public function create($templateId) {
        if (!array_key_exists($templateId, $this->housingTemplates)) {
            return null;
        }

        $template = $this->housingTemplates[$templateId];
        $house = new House();
        $house->Id = $templateId; // Simulating unique ID assignment
        $house->Template = $template;
        $house->AccountId = rand(1, 100); // Sample account ID
        $house->OwnerId = rand(1, 100); // Sample owner ID
        $house->Position = (object) ['X' => 0, 'Y' => 0, 'Z' => 0]; // Example position
        $house->CurrentStep = 0;
        $house->NumAction = 0;
        $house->Permission = 2;
        
        $this->houses[$house->Id] = $house;
        return $house;
    }

    public function load() {
        // Simulating loading templates from a database
        $template = new HousingTemplate();
        $template->Id = 1;
        $template->Name = "Sample House";
        $this->housingTemplates[$template->Id] = $template;

        // Simulating loading houses
        $this->create(1);
    }

    public function spawnAll() {
        foreach ($this->houses as $house) {
            // Simulating spawning houses
            echo "Spawning house ID: " . $house->Id . PHP_EOL;
        }
    }
}

function main() {
    $housingManager = new HousingManager();
    $housingManager->load();
    $housingManager->spawnAll();
}

main();
--EXPECT--
