--TEST--
Translated seed (inferredbugs-csharp: AniAPI/27)
--FILE--
<?php

class Anime {
    public $Id;
    public $AnilistId;
    public $CreationDate;
    public $UpdateDate;
    public $Titles;
    public $Descriptions;
}

class AnimeCollection {
    private $collection = [];

    public function add(Anime &$document) {
        $this->collection[] = $document;
    }

    public function count() {
        return count($this->collection);
    }

    public function delete($id) {
        // Not implemented
    }

    public function edit(Anime &$document) {
        foreach ($this->collection as &$item) {
            if ($item->Id == $document->Id) {
                $item = $document;
                break;
            }
        }
    }

    public function get($id) {
        foreach ($this->collection as $item) {
            if ($item->Id == $id) {
                return $item;
            }
        }
        return null;
    }

    public function exists(Anime &$document, $updateValues = true) {
        foreach ($this->collection as $reference) {
            if ($reference->AnilistId == $document->AnilistId) {
                if ($updateValues) {
                    $document->Id = $reference->Id;
                    $document->CreationDate = $reference->CreationDate;
                    $document->UpdateDate = $reference->UpdateDate;
                    $document->Titles = $reference->Titles;
                    $document->Descriptions = $reference->Descriptions;
                }
                return true;
            }
        }
        return false;
    }
}

$animeCollection = new AnimeCollection();

$anime1 = new Anime();
$anime1->Id = 1;
$anime1->AnilistId = 123;
$anime1->Titles = ["Title1"];
$animeCollection->add($anime1);

$anime2 = new Anime();
$anime2->AnilistId = 123;

if ($animeCollection->exists($anime2)) {
    echo "Anime exists with ID: " . $anime2->Id . "\n";
} else {
    echo "Anime does not exist.\n";
}

echo "Total Animes: " . $animeCollection->count() . "\n";
--EXPECT--
