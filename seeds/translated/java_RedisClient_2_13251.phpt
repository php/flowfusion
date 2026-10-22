--TEST--
Translated seed (inferredbugs-java: RedisClient/2)
--FILE--
<?php
$propertyFile = "./conf.properties";

function read($key) {
    global $propertyFile;
    $props = getProperty();
    return isset($props[$key]) ? $props[$key] : null;
}

function readAmount($amount) {
    $servers = read($amount);
    return $servers === null ? "0" : $servers;
}

function readMaxId($maxid) {
    $id = read($maxid);
    return $id === null ? "0" : $id;
}

function getProperty() {
    global $propertyFile;
    $props = [];
    if (!file_exists($propertyFile)) {
        file_put_contents($propertyFile, "");
    }
    $lines = file($propertyFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($key, $value) = explode("=", $line, 2);
        $props[trim($key)] = trim($value);
    }
    return $props;
}

function write($key, $value) {
    global $propertyFile;
    $props = getProperty();
    $props[$key] = $value;
    file_put_contents($propertyFile, implode(PHP_EOL, array_map(function($k, $v) { return "$k=$v"; }, array_keys($props), $props)));
}

function delete($key) {
    global $propertyFile;
    $props = getProperty();
    unset($props[$key]);
    file_put_contents($propertyFile, implode(PHP_EOL, array_map(function($k, $v) { return "$k=$v"; }, array_keys($props), $props)));
}

// Example usage
write("port", "8080");
echo readAmount("port") . PHP_EOL;
delete("port");
echo readAmount("port") . PHP_EOL;
?>
--EXPECT--
