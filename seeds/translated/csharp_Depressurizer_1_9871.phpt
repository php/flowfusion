--TEST--
Translated seed (inferredbugs-csharp: Depressurizer/1)
--FILE--
<?php

class GameDBEntry {
    public $Id;
    public $Name;
    public $Genre;
    public $Type;

    public function __construct($id, $name, $genre, $type) {
        $this->Id = $id;
        $this->Name = $name;
        $this->Genre = $genre;
        $this->Type = $type;
    }
}

class GameDB {
    public $Games = [];

    public function AddGame($game) {
        $this->Games[$game->Id] = $game;
    }

    public function RemoveGame($id) {
        unset($this->Games[$id]);
    }
}

function displayGames($games) {
    foreach ($games as $game) {
        echo "Name: {$game->Name}, Id: {$game->Id}, Genre: {$game->Genre}, Type: {$game->Type}\n";
    }
}

function updateSelectedGames($gameDB, $selectedIds) {
    foreach ($selectedIds as $id) {
        if (isset($gameDB->Games[$id])) {
            $game = $gameDB->Games[$id];
            echo "Updating game: {$game->Name}\n";
        } else {
            echo "Removing game with Id: {$id}\n";
            $gameDB->RemoveGame($id);
        }
    }
}

$gameList = new GameDB();
$gameList->AddGame(new GameDBEntry(1, "Game A", "Action", "Game"));
$gameList->AddGame(new GameDBEntry(2, "Game B", "Adventure", "Game"));
$gameList->AddGame(new GameDBEntry(3, "Game C", "Strategy", "Game"));

// Simulating selection of games to update
$selectedIds = [1, 3, 4]; // 4 does not exist and will be removed
updateSelectedGames($gameList, $selectedIds);

displayGames($gameList->Games);
--EXPECT--
