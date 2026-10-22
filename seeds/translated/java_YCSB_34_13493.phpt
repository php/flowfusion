--TEST--
Translated seed (inferredbugs-java: YCSB/34)
--FILE--
<?php
$INIT_COUNT = 0;
$client = null;
$databaseName = "ycsb";
$queryText = "SELECT * FROM my_collection WHERE myid >= ?";

function init() {
    global $INIT_COUNT, $client;
    $INIT_COUNT++;
    if ($client !== null) {
        return;
    }
    $client = new PDO('mysql:host=localhost;dbname=ycsb', 'username', 'password');
}

function read($table, $key) {
    global $client;
    $query = "SELECT * FROM $table WHERE id = :key";
    $stmt = $client->prepare($query);
    $stmt->execute(['key' => $key]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function scan($table, $startkey, $recordcount) {
    global $client, $queryText;
    $stmt = $client->prepare($queryText);
    $stmt->execute([$startkey]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function update($table, $key, $values) {
    global $client;
    $setClause = implode(', ', array_map(function($k) { return "$k = :$k"; }, array_keys($values)));
    $query = "UPDATE $table SET $setClause WHERE id = :id";
    $values['id'] = $key;
    $stmt = $client->prepare($query);
    return $stmt->execute($values);
}

function insert($table, $key, $values) {
    global $client;
    $fields = implode(', ', array_keys($values));
    $placeholders = implode(', ', array_map(function($k) { return ":$k"; }, array_keys($values)));
    $query = "INSERT INTO $table (id, $fields) VALUES (:id, $placeholders)";
    $values['id'] = $key;
    $stmt = $client->prepare($query);
    return $stmt->execute($values);
}

function cleanup() {
    global $INIT_COUNT, $client;
    $INIT_COUNT--;
    if ($INIT_COUNT === 0) {
        $client = null;
    }
}

init();
$data = read('my_collection', '123');
$results = scan('my_collection', '100', 10);
update('my_collection', '123', ['name' => 'test']);
insert('my_collection', '124', ['name' => 'new_entry']);
cleanup();
?>
--EXPECT--
