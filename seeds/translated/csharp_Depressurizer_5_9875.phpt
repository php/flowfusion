--TEST--
Translated seed (inferredbugs-csharp: Depressurizer/5)
--FILE--
<?php

class Game {
    public $id;
    public $name;
    public $category;
    public $favorite;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
        $this->category = null;
        $this->favorite = false;
    }
}

class GameData {
    public $games = [];
    public $categories = [];

    public function addGame($name) {
        $id = count($this->games) + 1; // Simple ID generation
        $this->games[$id] = new Game($id, $name);
        return $this->games[$id];
    }

    public function addCategory($name) {
        if (!in_array($name, $this->categories)) {
            $this->categories[] = $name;
        }
    }

    public function removeCategory($name) {
        if (($key = array_search($name, $this->categories)) !== false) {
            unset($this->categories[$key]);
        }
    }

    public function setCategory($gameId, $categoryName) {
        if (isset($this->games[$gameId])) {
            $this->games[$gameId]->category = $categoryName;
        }
    }
}

function manualImport($gameData) {
    // Simulates loading a game list
    $gameData->addGame("Game A");
    $gameData->addGame("Game B");
}

function manualExport($gameData) {
    // Simulate exporting game data
    foreach ($gameData->games as $game) {
        echo "Exporting game: " . $game->name . "\n";
    }
}

$gameData = new GameData();
$gameData->addCategory("Action");
$gameData->addCategory("Adventure");

manualImport($gameData);
$gameData->setCategory(1, "Action");
$gameData->setCategory(2, "Adventure");

manualExport($gameData);
--EXPECT--
