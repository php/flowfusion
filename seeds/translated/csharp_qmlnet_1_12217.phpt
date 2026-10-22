--TEST--
Translated seed (inferredbugs-csharp: qmlnet/1)
--FILE--
<?php
class TestType1 {
    public function TestMethod() {}
    public int $TestProperty = 0;
}
class TestType2 {
    public function TestMethod(int $parameter1, TestType1 $parameter2) {}
}
class TestType3 {
    public function TestMethod(): ?TestType3 { return null; }
}
class TestType4 {
    public function TestMethod() {}
}
class TestType5 {
    public function ThisIsMethodName() {}
}
class TestType6 {
    public string $Property = '';
}
class TestType7 {
    public DateTime $testSignal;
    public TestType1 $objectValue;
}
class TestType8 {
    public string $Property = '';
}
class TestType9 {
    public string $Property = '';
}
class TestType10 {
    public string $Property = '';
}
class TestType11 {
    public function TestFunction(): ?TestType11_InnerType { return null; }
}
class TestType11_InnerType {}
class TestType12 {
    public function LocalMethod() {}
    public static function StaticMethod() {}
}
class TestType13 {
    public array $Property = [];
}

$classes = [
    TestType1::class,
    TestType2::class,
    TestType3::class,
    TestType4::class,
    TestType5::class,
    TestType6::class,
    TestType7::class,
    TestType8::class,
    TestType9::class,
    TestType10::class,
    TestType11::class,
    TestType12::class,
    TestType13::class,
];

foreach ($classes as $class) {
    $r = new ReflectionClass($class);
    $r->getName();
    $r->getShortName();
    $r->getMethods();
    $r->getProperties();
    foreach ($r->getMethods() as $m) {
        $m->getName();
        $m->isStatic();
        $m->getNumberOfParameters();
        $m->getReturnType();
        foreach ($m->getParameters() as $p) {
            $p->getName();
            $p->getType();
        }
    }
    foreach ($r->getProperties() as $p) {
        $p->getName();
        $p->getType();
    }
}

$rt = new ReflectionClass(TestType11_InnerType::class);
$rt->getMethods();
$rt->getProperties();

$dt = new DateTime();
$dt->format(DateTime::ATOM);
?>
--EXPECT--
