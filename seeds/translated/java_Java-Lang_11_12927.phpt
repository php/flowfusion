--TEST--
Translated seed (inferredbugs-java: Java-Lang/11)
--FILE--
<?php

class A {
    public int $i;
    public int $j;
    public int $k;
    public ?string $s = null;
}

class B {
    public float $x = 0;
    public float $y = 0;
    public float $z = 0;
    public float $w = 0;
}

$a = new A();
$a->i = 111;
$a->j = -222;
$a->k = 333;
$a->s = "Hello";

$bytes = pack("iii", $a->i, $a->j, $a->k);

$a2 = new A();
$a2->i = 1;
$a2->j = 2;
$a2->k = 3;
$unpacked = unpack("i3", $bytes);

$a2->i = $unpacked[1];
$a2->j = $unpacked[2];
$a2->k = $unpacked[3];

$position = 12;

$position += 4 * 3;

$b = new B();
$b->z = $a2->i + $a2->j;
$b->w = $a2->k;
--EXPECT--
