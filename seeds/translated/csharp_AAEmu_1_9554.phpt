--TEST--
Translated seed (inferredbugs-csharp: AAEmu/1)
--FILE--
<?php

class House {
    public $TlId;
    public $ObjId;
    public $Template;
    public $TemplateId;
    public $Faction;
    public $Name;
    public $MaxHp;
    public $Hp;
    public $CurrentStep;

    public function spawn() {
        // Simulate house spawning logic
    }
}

class HousingTemplate {
    public $Id;
    public $Name;
    public $Hp;
    public $Taxation;

    public function __construct($id, $name, $hp) {
        $this->Id = $id;
        $this->Name = $name;
        $this->Hp = $hp;
    }
}

class HouseManager {
    private $housingTemplates = [];
    private $houses = [];

    public function create($templateId) {
        if (!array_key_exists($templateId, $this->housingTemplates)) {
            return null;
        }

        $template = $this->housingTemplates[$templateId];
        $house = new House();
        $house->TlId = rand(1, 100); 
        $house->ObjId = rand(1, 100); 
        $house->Template = $template;
        $house->TemplateId = $template->Id;
        $house->Name = $template->Name;
        $house->MaxHp = $house->Hp = $template->Hp;
        $house->CurrentStep = -1;

        return $house;
    }

    public function load() {
        // Simulating loading templates
        $this->housingTemplates[1] = new HousingTemplate(1, "Cottage", 100);
        $this->housingTemplates[2] = new HousingTemplate(2, "Villa", 200);
    }

    public function build($designId) {
        if (empty($this->houses)) {
            echo "No houses built yet.\n";
        }
        $house = $this->create($designId);
        if ($house !== null) {
            $this->houses[$house->ObjId] = $house;
            $house->spawn();
            echo "House built with ID: {$house->ObjId}\n";
        } else {
            echo "Failed to build house: Template ID $designId not found.\n";
        }
    }
}

function main() {
    $houseManager = new HouseManager();
    $houseManager->load();
    $houseManager->build(1); 
    $houseManager->build(2); 
    $houseManager->build(3); 
}

main();
--EXPECT--
