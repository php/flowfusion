--TEST--
Translated seed (inferredbugs-csharp: AniAPI/25)
--FILE--
<?php

class AnimeSuggestion {
    public $Id;
    public $AnimeID;
    public $Source;
    public $Title;
    public $CreationDate;
    public $UpdateDate;
}

class AnimeSuggestionCollection {
    private $collection = [];

    public function add(AnimeSuggestion &$document) {
        $this->collection[] = $document;
    }

    public function count() {
        return count($this->collection);
    }

    public function edit(AnimeSuggestion &$document) {
        foreach ($this->collection as &$item) {
            if ($item->Id === $document->Id) {
                $item = $document;
            }
        }
    }

    public function exists(AnimeSuggestion &$document, $updateValues = true) {
        foreach ($this->collection as $reference) {
            if ($reference->AnimeID === $document->AnimeID && 
                $reference->Source === $document->Source && 
                $reference->Title === $document->Title) {
                
                if ($updateValues) {
                    $document->Id = $reference->Id;
                    $document->CreationDate = $reference->CreationDate;
                    $document->UpdateDate = $reference->UpdateDate;
                }
                return true;
            }
        }
        return false;
    }
}

function main() {
    $collection = new AnimeSuggestionCollection();

    $suggestion1 = new AnimeSuggestion();
    $suggestion1->AnimeID = 1;
    $suggestion1->Source = 'Source A';
    $suggestion1->Title = 'Title A';
    $suggestion1->Id = 1;
    $suggestion1->CreationDate = '2023-01-01';
    $suggestion1->UpdateDate = '2023-01-02';

    $collection->add($suggestion1);

    $suggestion2 = new AnimeSuggestion();
    $suggestion2->AnimeID = 1;
    $suggestion2->Source = 'Source A';
    $suggestion2->Title = 'Title A';

    if ($collection->exists($suggestion2)) {
        $collection->edit($suggestion2);
    }

    echo "Count: " . $collection->count() . "\n";
}

main();
--EXPECT--
