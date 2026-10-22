--TEST--
Translated seed (inferredbugs-csharp: Nustache/3)
--FILE--
<?php
$target = ['0' => 'zero', '1' => 'one', 'a' => 'alpha'];
$name = '0';
if (is_array($target) && array_key_exists($name, $target)) {
    $value = $target[$name];
} elseif (is_object($target) && isset($target->$name)) {
    $value = $target->$name;
} else {
    $value = null;
}

$objects = [
    (object)['Name' => 'Alice', 'value' => 1],
    (object)['name' => 'Bob', 'Value' => 2],
];

$lookup = 'name';
$result = null;
foreach ($objects as $obj) {
    foreach (get_object_vars($obj) as $k => $v) {
        if (strcasecmp($k, $lookup) === 0) {
            $result = $v;
            break 2;
        }
    }
}

$list = [10, 20, 30];
$indexName = '1';
if (ctype_digit($indexName)) {
    $index = (int)$indexName;
    if ($index >= 0 && $index < count($list)) {
        $item = $list[$index];
    }
}

$xml = new SimpleXMLElement('<root><Child>ok</Child></root>');
$xmlName = 'child';
$xmlValue = null;
foreach ($xml->children() as $child) {
    if (strcasecmp($child->getName(), $xmlName) === 0) {
        $xmlValue = (string)$child;
        break;
    }
}

echo "done\n";
--EXPECT--
