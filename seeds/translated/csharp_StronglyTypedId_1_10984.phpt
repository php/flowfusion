--TEST--
Translated seed (inferredbugs-csharp: StronglyTypedId/1)
--FILE--
<?php
class StringId {
    public string $value;
    function __construct(string $value) { $this->value = $value; }
    function equals($other): bool { return $other instanceof self && $this->value === $other->value; }
    function __toString(): string { return $this->value; }
}

$id = "some-value";
$foo1 = new StringId($id);
$foo2 = new StringId($id);
$foo3 = new StringId("other value");

$empty = new StringId("");

json_encode($foo1->value);
json_encode($foo1);
json_decode(json_encode($foo1->value), true);
json_decode(json_encode($foo1), true);

$store = [];
$store[] = ["id" => uniqid("", true), "name" => $foo1];
$store[] = ["id" => uniqid("", true), "name" => $foo3];

$encoded = json_encode($store);
$decoded = json_decode($encoded, true);

$round1 = new StringId($decoded[0]["name"]["value"]);
$round2 = new StringId($decoded[1]["name"]["value"]);

$map = [];
$map[(string)$foo1] = 1;
$map[(string)$foo2] = 2;
$map[(string)$foo3] = 3;

$copy = $map;
$copy[(string)$empty] = 4;

echo $foo1->equals($foo2) ? "" : "";
echo !$foo1->equals($foo3) ? "" : "";
echo $empty->value === "" ? "" : "";
echo $round1->equals($foo1) ? "" : "";
echo $round2->equals($foo3) ? "" : "";
echo count($decoded) === 2 ? "" : "";
echo count($copy) === 3 ? "" : "";
?>
--EXPECT--
