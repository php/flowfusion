--TEST--
Translated seed (inferredbugs-csharp: Cosmonaut/2)
--FILE--
<?php

class MockDatabase {
    public $id;
}

class MockDocumentCollection {
    public $id;
}

class MockOffer {}

class MockDocumentClient {
    public function __construct() {
        $this->authKey = '';
        $this->serviceEndpoint = 'http://test.com';
    }

    public function readDatabase($id) {
        return new MockDatabaseResponse(new MockDatabase($id));
    }

    public function readDocumentCollection($id) {
        return new MockDocumentCollectionResponse(new MockDocumentCollection($id));
    }

    public function createDatabaseQuery() {
        return new ArrayIterator([new MockDatabase('databaseName')]);
    }

    public function createDocumentCollectionQuery() {
        return new ArrayIterator([new MockDocumentCollection('dummies')]);
    }

    public function createOfferQuery() {
        return new ArrayIterator([new MockOffer()]);
    }
}

class MockDatabaseResponse {
    private $resource;
    
    public function __construct($resource) {
        $this->resource = $resource;
    }

    public function getResource() {
        return $this->resource;
    }
}

class MockDocumentCollectionResponse {
    private $resource;
    
    public function __construct($resource) {
        $this->resource = $resource;
    }

    public function getResource() {
        return $this->resource;
    }
}

function getFakeDocumentClient($databaseName = 'databaseName') {
    $mockDocumentClient = new MockDocumentClient();
    $mockDocumentClient->readDatabase($databaseName);
    $mockDocumentClient->readDocumentCollection('dummies');
    return $mockDocumentClient;
}

$client = getFakeDocumentClient();
--EXPECT--
