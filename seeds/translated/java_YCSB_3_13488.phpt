--TEST--
Translated seed (inferredbugs-java: YCSB/3)
--FILE--
<?php

class MongoDbClient {
    private static $client;
    private static $db;
    private static $bulkInserts = [];
    private static $batchSize = 1;

    public static function init($url) {
        if (!self::$client) {
            self::$client = new MongoDB\Client($url);
            self::$db = self::$client->selectDatabase('ycsb');
        }
    }

    public static function insert($table, $key, $values) {
        $toInsert = ['_id' => $key];
        foreach ($values as $field => $value) {
            $toInsert[$field] = $value;
        }
        self::$bulkInserts[] = $toInsert;
        if (count(self::$bulkInserts) == self::$batchSize) {
            self::$db->{$table}->insertMany(self::$bulkInserts);
            self::$bulkInserts = [];
        }
    }

    public static function cleanup() {
        self::$client = null;
        self::$db = null;
    }
}

MongoDbClient::init("mongodb://localhost:27017");
MongoDbClient::insert("test_table", "key1", ["field1" => "value1", "field2" => "value2"]);
MongoDbClient::insert("test_table", "key2", ["field1" => "value3", "field2" => "value4"]);
MongoDbClient::cleanup();
--EXPECT--
