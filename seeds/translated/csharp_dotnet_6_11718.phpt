--TEST--
Translated seed (inferredbugs-csharp: dotnet/6)
--FILE--
<?php
class Element {
    public string $id;
    public string $name;
    public array $links = [];
    function __construct(string $name) { $this->id = uniqid('', true); $this->name = $name; }
    function uses(Element $e): void { $this->links[] = $e; }
}
class Person extends Element {}
class Container extends Element {}
class SoftwareSystem extends Element {
    public array $containers = [];
    function addContainer(string $name): Container { return $this->containers[] = new Container($name); }
}
class View {
    public SoftwareSystem $system;
    public array $elements = [];
    function __construct(SoftwareSystem $system) { $this->system = $system; }
    function addAllContainers(): void { foreach ($this->system->containers as $c) $this->elements[$c->id] = $c; }
    function remove(Element $e): void { unset($this->elements[$e->id]); }
    function addNearestNeighbours(?Element $e): void {
        if (!$e) return;
        $this->elements[$e->id] = $e;
        foreach ($e->links as $n) $this->elements[$n->id] = $n;
        foreach ([$this->system] as $s) if ($s !== $e && in_array($e, $s->links, true)) $this->elements[$s->id] = $s;
        foreach ($this->system->containers as $c) if (in_array($e, $c->links, true)) $this->elements[$c->id] = $c;
    }
}

$system = new SoftwareSystem("The System");
$view = new View($system);

$web = $system->addContainer("Web Application");
$db = $system->addContainer("Database");
$view->addAllContainers();
$view->remove($web);

$userA = new Person("User A");
$userB = new Person("User B");
$sysA = new SoftwareSystem("System A");
$sysB = new SoftwareSystem("System B");
$web->uses($db);
$userA->uses($sysA);
$sysA->uses($system);
$system->uses($sysB);
$sysB->uses($userB);
$view->addNearestNeighbours($system);
echo count($view->elements), "\n";
?>
--EXPECT--
