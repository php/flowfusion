--TEST--
Translated seed (inferredbugs-csharp: AAEmu/19)
--FILE--
<?php

class HousingManager {
    private const MAX_HEAVY_TAX_COUNTED = 10;
    private $housingTemplates = [];
    private $houses = [];
    private $removedHousings = [];
    
    public function getByAccountId($accountId) {
        $values = [];
        foreach ($this->houses as $id => $house) {
            if ($house['accountId'] === $accountId) {
                $values[$id] = $house;
            }
        }
        return count($values);
    }

    public function createHouse($templateId, $accountId) {
        $template = $this->housingTemplates[$templateId] ?? null;
        if (!$template) return null;

        $house = [
            'id' => count($this->houses) + 1,
            'templateId' => $templateId,
            'accountId' => $accountId,
            'hp' => $template['hp'],
            'protectionEndDate' => time() + 604800 // 7 days
        ];
        
        $this->houses[$house['id']] = $house;
        return $house;
    }

    public function loadTemplates() {
        $this->housingTemplates = [
            1 => ['id' => 1, 'hp' => 100],
            2 => ['id' => 2, 'hp' => 200],
        ];
    }

    public function updateTaxInfo($house) {
        $now = time();
        $isDemolished = ($house['protectionEndDate'] <= $now);
        if ($isDemolished) {
            $this->removeHouse($house['id']);
        }
    }

    public function removeHouse($houseId) {
        unset($this->houses[$houseId]);
        $this->removedHousings[] = $houseId;
    }
    
    public function checkHousingTaxes() {
        foreach ($this->houses as $house) {
            $this->updateTaxInfo($house);
        }
    }
}

$housingManager = new HousingManager();
$housingManager->loadTemplates();
$housingManager->createHouse(1, 123); // Create a house for accountId 123
$housingManager->checkHousingTaxes(); // Check for taxes and update house statuses
--EXPECT--
