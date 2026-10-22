--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/14)
--FILE--
<?php
class TestTable {
    public $pk;
    public $ck1;
    public $ck2;
    public $f1;
}

$e = new TestTable();
$e->pk = "x";
$e->ck1 = 1;
$e->ck2 = 2;
$e->f1 = 3;

$insert = 'INSERT INTO "x_t"("x_pk", "x_ck1", "x_ck2", "x_f1") VALUES (\'x\', 1, 2, 3)';
$update1 = 'UPDATE "x_t" SET "x_f1" = NULL  WHERE "x_pk" = \'y\'  AND "x_ck1" = 3  AND "x_ck2" = 4 ';
$update2 = 'UPDATE "x_t" SET "x_f1" = 10  WHERE "x_pk" = \'y\'  AND "x_ck1" = 3  AND "x_ck2" = 4 ';

echo $insert, PHP_EOL, $update1, PHP_EOL, $update2, PHP_EOL;
?>
--EXPECT--
