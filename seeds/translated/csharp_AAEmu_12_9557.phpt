--TEST--
Translated seed (inferredbugs-csharp: AAEmu/12)
--FILE--
<?php

class House {
    public $TlId;
    public $ObjId;
    public $Template;
    public $TemplateId;
    public $Faction;
    public $Name;
    public $Hp;
    public $MaxHp;

    public function __construct() {
        $this->MaxHp = 100; // Example max HP
        $this->Hp = $this->MaxHp;
    }
}

class HousingTemplate {
    public $Id;
    public $Name;
    public $Taxation;
}

class HousingManager {
    private $housingTemplates;
    private $houses;
    private $housesTl;

    public function __construct() {
        $this->housingTemplates = [];
        $this->houses = [];
        $this->housesTl = [];
    }

    public function create($templateId, $objectId = 0, $tlId = 0) {
        if (!isset($this->housingTemplates[$templateId])) {
            return null;
        }

        $template = $this->housingTemplates[$templateId];

        $house = new House();
        $house->TlId = $tlId > 0 ? $tlId : rand(1, 65535);
        $house->ObjId = $objectId > 0 ? $objectId : rand(1, 1000000);
        $house->Template = $template;
        $house->TemplateId = $template->Id;
        $house->Name = $template->Name;
        $house->Hp = $house->MaxHp;

        return $house;
    }

    public function load() {
        $this->housingTemplates[1] = (new HousingTemplate())->setId(1)->setName("Basic House");
        $this->housingTemplates[1]->Taxation = (object)['Tax' => 50];

        $house1 = $this->create(1);
        $house1->ObjId = 1;
        $this->houses[$house1->ObjId] = $house1;
        $this->housesTl[$house1->TlId] = $house1;
    }

    public function constructHouseTax($designId) {
        $template = $this->housingTemplates[$designId];
        $baseTax = $template->Taxation->Tax;
        $depositTax = $baseTax * 2;
        $totalTax = $baseTax + $depositTax;

        echo "Base Tax: $baseTax, Deposit Tax: $depositTax, Total Tax: $totalTax\n";
    }
}

$manager = new HousingManager();
$manager->load();
$manager->constructHouseTax(1);
--EXPECT--
