--TEST--
Translated seed (inferredbugs-csharp: oxyplot-avalonia/1)
--FILE--
<?php
class ExampleAttribute {
    public string $title;
    public string $description;
    public function __construct(string $title, string $description) {
        $this->title = $title;
        $this->description = $description;
    }
}

class Example {
    public string $title;
    public string $description;
    public string $className;
    public function __construct(string $className, string $title, string $description) {
        $this->className = $className;
        $this->title = $title;
        $this->description = $description;
    }
}

#[ExampleAttribute('Zeta', 'last')]
class ZetaExample {}

#[ExampleAttribute('Alpha', 'first')]
class AlphaExample {}

#[ExampleAttribute('Alpha', 'duplicate title')]
class AlphaExample2 {}

$classes = get_declared_classes();
$examples = [];
foreach ($classes as $class) {
    $r = new ReflectionClass($class);
    $attrs = $r->getAttributes(ExampleAttribute::class);
    if ($attrs) {
        $ea = $attrs[0]->newInstance();
        $examples[] = new Example($class, $ea->title, $ea->description);
    }
}

usort($examples, fn($a, $b) => $a->title <=> $b->title);

foreach ($examples as $e) {
    $x = ['class' => $e->className, 'title' => $e->title, 'description' => $e->description];
    json_encode($x);
}
?>
--EXPECT--
