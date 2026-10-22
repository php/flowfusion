--TEST--
Translated seed (inferredbugs-java: OSM2World/13)
--FILE--
<?php

class Indoor {

    private $elements = [];
    private $buildingPart;
    private $walls = [];
    private $rooms = [];
    private $areas = [];
    private $corridors = [];

    public function __construct(array $indoorElements, $buildingPart) {
        $this->buildingPart = $buildingPart;

        foreach ($indoorElements as $element) {
            if (isset($element['tags']['level'])) {
                $elementLevel = (float)$element['tags']['level'];

                if ($elementLevel >= $buildingPart['minLevel'] && $elementLevel <= $buildingPart['buildingLevels']) {
                    $this->elements[] = $element;
                }
            }
        }

        $this->createComponents();
    }

    private function createComponents() {
        foreach ($this->elements as $element) {
            switch ($element['tags']['indoor']) {
                case 'wall':
                    $this->walls[] = new IndoorWall($this->buildingPart, $element);
                    break;

                case 'room':
                    $this->rooms[] = new IndoorRoom($element, $this->buildingPart);
                    break;

                case 'area':
                    $this->areas[] = new IndoorArea($this->buildingPart, $element);
                    break;

                case 'corridor':
                    $this->corridors[] = new Corridor($this->buildingPart, $element);
                    break;
            }
        }
    }
}

$indoorElements = [
    ['tags' => ['level' => '1', 'indoor' => 'wall']],
    ['tags' => ['level' => '2', 'indoor' => 'room']],
    ['tags' => ['level' => '3', 'indoor' => 'area']],
    ['tags' => ['level' => '4', 'indoor' => 'corridor']],
];
$buildingPart = ['minLevel' => 1, 'buildingLevels' => 4];

$indoor = new Indoor($indoorElements, $buildingPart);
--EXPECT--
