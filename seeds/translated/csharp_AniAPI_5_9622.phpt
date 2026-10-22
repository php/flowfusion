--TEST--
Translated seed (inferredbugs-csharp: AniAPI/5)
--FILE--
<?php

class OAuthClient {
    public $Id;
    public $ClientID;
    public $CreationDate;
    public $UpdateDate;
}

class OAuthClientCollection {
    private $collection = [];

    public function add(OAuthClient $document) {
        do {
            $document->ClientID = $this->generateGuid();
        } while ($this->exists($document));

        $this->collection[] = $document;
    }

    public function count() {
        return count($this->collection);
    }

    public function delete($id) {
        foreach ($this->collection as $index => $doc) {
            if ($doc->Id == $id) {
                unset($this->collection[$index]);
                break;
            }
        }
    }

    public function edit(OAuthClient $document) {
        foreach ($this->collection as &$doc) {
            if ($doc->Id == $document->Id) {
                $doc = $document;
                break;
            }
        }
    }

    public function exists(OAuthClient $document) {
        foreach ($this->collection as $doc) {
            if ($doc->ClientID == $document->ClientID) {
                return true;
            }
        }
        return false;
    }

    public function get($id) {
        foreach ($this->collection as $doc) {
            if ($doc->Id == $id) {
                return $doc;
            }
        }
        return null;
    }

    private function generateGuid() {
        return sprintf('%s-%s-%s-%s-%s',
            bin2hex(random_bytes(4)), bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2)), bin2hex(random_bytes(2)),
            bin2hex(random_bytes(6))
        );
    }
}

function main() {
    $clientCollection = new OAuthClientCollection();
    
    $client1 = new OAuthClient();
    $client1->Id = 1;
    
    $clientCollection->add($client1);
    $clientCollection->add(new OAuthClient()); // Add another client to trigger GUID generation
    $clientCollection->delete(1);
    $clientCollection->edit(new OAuthClient());
    $clientCollection->count();
}

main();
--EXPECT--
