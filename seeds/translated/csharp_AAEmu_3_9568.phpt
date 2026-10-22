--TEST--
Translated seed (inferredbugs-csharp: AAEmu/3)
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
        $this->CurrentStep = -1;
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

    public function create($templateId, $objectId = 0, $tlId = 0) {
        if (!isset($this->housingTemplates[$templateId])) {
            return null;
        }

        $template = $this->housingTemplates[$templateId];
        $house = new House();
        $house->TlId = $tlId > 0 ? $tlId : $this->getNextId(); 
        $house->ObjId = $objectId > 0 ? $objectId : $this->getNextId();
        $house->Template = $template;
        $house->TemplateId = $template->Id;
        $house->Name = $template->Name;
        $house->MaxHp = $house->Hp = $template->Hp;

        return $house;
    }

    public function load() {
        $this->housingTemplates[1] = new HousingTemplate(1, "Template1", 100);
        $this->housingTemplates[2] = new HousingTemplate(2, "Template2", 200);
    }

    private function getNextId() {
        static $id = 0;
        return ++$id;
    }
}

function main() {
    $housingManager = new HousingManager();
    $housingManager->load();
    $house = $housingManager->create(1);
    var_dump($house);
}

main();
--EXPECT--
