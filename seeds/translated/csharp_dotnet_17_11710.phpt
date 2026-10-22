--TEST--
Translated seed (inferredbugs-csharp: dotnet/17)
--FILE--
<?php
class Element {
    public string $name;
    public string $description;
    public array $relationships = [];
    function __construct(string $name, string $description) { $this->name = $name; $this->description = $description; }
    function uses(Element $destination, string $description, string $technology) {
        foreach ($this->relationships as $r) if ($r->destination === $destination && $r->description === $description && $r->technology === $technology) return null;
        $r = new Relationship($this, $destination, $description, $technology);
        $this->relationships[] = $r;
        return $r;
    }
}
class Relationship {
    public Element $source;
    public Element $destination;
    public string $description;
    public string $technology;
    function __construct(Element $source, Element $destination, string $description, string $technology) {
        $this->source = $source; $this->destination = $destination; $this->description = $description; $this->technology = $technology;
    }
}
$a = new Element("Element 1", "Description");
$b = new Element("Element 2", "Description");
$r1 = $a->uses($b, "Uses", "");
$r2 = $a->uses($b, "Uses", "");
$r3 = $a->uses($b, "Uses in some way", "");
$r4 = $a->uses($b, "Uses in another way", "");
if ($r1 === null || $r2 !== null || count($a->relationships) !== 3) exit(1);
if ($a->relationships[0] !== $r1 || $a->relationships[1] !== $r3 || $a->relationships[2] !== $r4) exit(1);
echo "";
?>
--EXPECT--
