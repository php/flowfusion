--TEST--
Translated seed (inferredbugs-csharp: AAEmu/5)
--FILE--
<?php

class HousingManager {
    private $housingTemplates = [];
    private $houses = [];

    public function getByAccountId(array $values, int $accountId) {
        foreach ($this->houses as $id => $house) {
            if ($house['accountId'] === $accountId) {
                $values[$id] = $house;
            }
        }
        return $values;
    }

    public function create(int $templateId, int $objectId = 0, int $tlId = 0) {
        if (!isset($this->housingTemplates[$templateId])) {
            return null;
        }

        $template = $this->housingTemplates[$templateId];
        $house = [];
        $house['tlId'] = $tlId > 0 ? $tlId : $this->getNextId();
        $house['objId'] = $objectId > 0 ? $objectId : $this->getNextId();
        $house['template'] = $template;
        $house['templateId'] = $template['id'];
        $house['accountId'] = 1; // Placeholder for demonstration
        $house['hp'] = $house['maxHp'] = $template['hp'];
        $house['currentStep'] = -1;

        return $house;
    }

    public function load() {
        // Simulate loading templates
        $this->housingTemplates[1] = [
            'id' => 1,
            'name' => 'Basic House',
            'hp' => 100
        ];
        
        // Simulate loading houses
        for ($i = 1; $i <= 10; $i++) {
            $house = $this->create(1);
            $house['id'] = $i;
            $house['accountId'] = $i;
            $this->houses[$house['id']] = $house;
        }
    }

    private function getNextId() {
        return count($this->houses) + 1;
    }

    public function save() {
        // Placeholder for saving logic
    }
}

function main() {
    $housingManager = new HousingManager();
    $housingManager->load();
    
    $housesForAccount = $housingManager->getByAccountId([], 1);
    var_dump($housesForAccount);
}

main();
--EXPECT--
