--TEST--
Translated seed (inferredbugs-java: Java-Lang/10)
--FILE--
<?php

class A {
    public int $i;
    public int $j;
    public int $k;
    public ?string $s = null;
}

class B {
    public float $x;
    public float $y;
    public float $z;
    public float $w;
}

function writeBytes(A $a): string {
    $data = '';
    $data .= pack('l', $a->i);
    $data .= pack('l', $a->j);
    $data .= pack('l', $a->k);
    return $data;
}

function readBytes(string $data, A $a): void {
    $a->i = unpack('l', substr($data, 0, 4))[1];
    $a->j = unpack('l', substr($data, 4, 4))[1];
    $a->k = unpack('l', substr($data, 8, 4))[1];
}

$a = new A();
$a->i = 111;
$a->j = -222;
$a->k = 333;

$db = writeBytes($a);

$a2 = new A();
readBytes($db, $a2);

$end_condition = $a2->i === 111 && $a2->j === -222 && $a2->k === 333;

if (!$end_condition) {
    exit(1);
}

exit(0);
--EXPECT--
