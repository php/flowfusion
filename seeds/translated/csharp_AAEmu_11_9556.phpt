--TEST--
Translated seed (inferredbugs-csharp: AAEmu/11)
--FILE--
<?php

class HousingTemplate {
    public $Id;
    public $Name;
    public $Taxation;
}

class House {
    public $Id;
    public $AccountId;
    public $OwnerId;
    public $TlId;
    public $Template;
    public $Hp;
    public $MaxHp;
    public $Position;
}

class HousingManager {
    private $housingTemplates = [];
    private $houses = [];
    private $housesTl = [];

    public function create($templateId, $objectId = 0, $tlId = 0) {
        if (!array_key_exists($templateId, $this->housingTemplates)) {
            return null;
        }

        $template = $this->housingTemplates[$templateId];

        $house = new House();
        $house->TlId = $tlId > 0 ? $tlId : $this->getNextTlId();
        $house->Id = $objectId > 0 ? $objectId : $this->getNextObjectId();
        $house->Template = $template;
        $house->Hp = $house->MaxHp;

        return $house;
    }

    public function load() {
        $this->housingTemplates = [];
        $this->houses = [];
        $this->housesTl = [];

        // Simulate loading templates
        $this->housingTemplates[1] = (new HousingTemplate())->setProperties(1, "House A", ["Tax" => 100]);
        $this->housingTemplates[2] = (new HousingTemplate())->setProperties(2, "House B", ["Tax" => 200]);

        // Create houses as edge case
        for ($i = 1; $i <= 2; $i++) {
            $house = $this->create($i);
            $house->AccountId = 1;
            $house->OwnerId = 1;
            $this->houses[$house->Id] = $house;
            $this->housesTl[$house->TlId] = $house;
        }
    }

    private function getNextTlId() {
        return count($this->housesTl) + 1;
    }

    private function getNextObjectId() {
        return count($this->houses) + 1;
    }
}

$manager = new HousingManager();
$manager->load();
--EXPECT--
