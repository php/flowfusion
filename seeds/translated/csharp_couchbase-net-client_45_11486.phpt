--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/45)
--FILE--
<?php
$rows = [
    ['type' => 'beer', 'name' => 'beer-sample'],
    ['type' => 'beer', 'name' => 'other'],
    ['type' => 'wine', 'name' => 'not-beer'],
];

$prepared = false;
for ($i = 0; $i < 3; $i++) {
    $result = [];
    foreach ($rows as $row) {
        if ($row['type'] === 'beer') {
            $result[] = $row;
        }
    }
    if (!$prepared) {
        $prepared = true;
    }
    foreach ($result as $row) {
        $x = $row['name'];
    }
}

$syntaxLike = "Hello World";
$greeting = (string)$syntaxLike;
if ($greeting !== "Hello World") {
    throw new Exception();
}

$bad = false;
$rows2 = [];
if ($bad) {
    $rows2[] = 1;
}
if (!empty($rows2)) {
    throw new Exception();
}

$cache = [];
for ($i = 0; $i < 2; $i++) {
    $key = $prepared ? 'prepared' : 'normal';
    if (!isset($cache[$key])) {
        $cache[$key] = [];
        foreach ($rows as $row) {
            $cache[$key][] = $row;
        }
    }
    foreach ($cache[$key] as $row) {
        $t = $row['type'];
    }
}

echo "";
?>
--EXPECT--
