--TEST--
Translated seed (inferredbugs-csharp: AAEmu/4)
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

    public function __construct() {
        // Initialize properties if needed
    }
}

class HousingTemplate {
    public $Id;
    public $Name;
    public $Hp;

    public function __construct($id, $name, $hp) {
        $this->Id = $id;
        $this->Name = $name;
        $this->Hp = $hp;
    }
}

class HousingManager {
    private $housingTemplates = [];
    private $houses = [];

    public function create($templateId, $objectId = 0) {
        if (!isset($this->housingTemplates[$templateId])) return null;

        $template = $this->housingTemplates[$templateId];

        $house = new House();
        $house->TlId = $objectId > 0 ? $objectId : rand(1, 1000);  // Simulating ID generation
        $house->ObjId = $objectId > 0 ? $objectId : rand(1, 1000);  // Simulating ID generation
        $house->Template = $template;
        $house->TemplateId = $template->Id;
        $house->Name = $template->Name;
        $house->MaxHp = $house->Hp = $template->Hp;
        $house->CurrentStep = -1;

        return $house;
    }

    public function load() {
        // Simulating loading templates
        $this->housingTemplates[1] = new HousingTemplate(1, "Small House", 100);
        $this->housingTemplates[2] = new HousingTemplate(2, "Large House", 200);
    }

    public function build($designId) {
        $house = $this->create($designId);
        if ($house) {
            $this->houses[] = $house;
        }
    }
}

function main() {
    $housingManager = new HousingManager();
    $housingManager->load();
    $housingManager->build(1);
    $housingManager->build(2);
}

main();
--EXPECT--
