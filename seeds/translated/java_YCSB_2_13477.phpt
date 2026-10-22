--TEST--
Translated seed (inferredbugs-java: YCSB/2)
--FILE--
<?php

class MongoDbClient {
    private static $databaseName;
    private static $mongoClient;
    private static $database;
    private static $batchSize = 1;
    private $bulkInserts = [];

    public function init($url) {
        if (self::$mongoClient == null) {
            self::$mongoClient = new MongoDB\Client($url);
            self::$database = self::$mongoClient->selectDatabase(self::$databaseName ?: 'ycsb');
        }
    }

    public function insert($table, $key, $values) {
        $toInsert = array_merge(['_id' => $key], $values);
        $this->bulkInserts[] = $toInsert;
        if (count($this->bulkInserts) == self::$batchSize) {
            self::$database->{$table}->insertMany($this->bulkInserts);
            $this->bulkInserts = [];
        }
    }

    public function delete($table, $key) {
        $result = self::$database->{$table}->deleteOne(['_id' => $key]);
        if ($result->getDeletedCount() == 0) {
            echo "Nothing deleted for key $key\n";
            return 1;
        }
        return 0;
    }

    public function read($table, $key) {
        $result = self::$database->{$table}->findOne(['_id' => $key]);
        return $result ? 0 : 1;
    }

    public function update($table, $key, $values) {
        $result = self::$database->{$table}->updateOne(['_id' => $key], ['$set' => $values]);
        if ($result->getMatchedCount() == 0) {
            echo "Nothing updated for key $key\n";
            return 1;
        }
        return 0;
    }
}

$client = new MongoDbClient();
$client->init('mongodb://localhost:27017');
$client->insert('testCollection', 'key1', ['field1' => 'value1']);
$client->delete('testCollection', 'key1');
$client->read('testCollection', 'key1');
$client->update('testCollection', 'key1', ['field1' => 'newValue']);
--EXPECT--
