--TEST--
Translated seed (inferredbugs-csharp: NPoco/1)
--FILE--
<?php

class Admin {
    public $UserId;
    public $Name;
    public $Savings;
    public $DateOfBirth;
    public $Age;
}

class Snapshot {
    private $obj;
    private $orig;
    private $map;

    public function __construct($obj, $map) {
        $this->obj = $obj;
        $this->map = $map;
        $this->orig = get_object_vars($obj);
    }

    public function changes() {
        $out = [];
        foreach (get_object_vars($this->obj) as $k => $v) {
            $o = array_key_exists($k, $this->orig) ? $this->orig[$k] : null;
            if ($o !== $v) {
                $out[] = (object)[
                    'Name' => $k,
                    'ColumnName' => $this->map[$k] ?? $k,
                    'OldValue' => $o,
                    'NewValue' => $v
                ];
            }
        }
        return $out;
    }

    public function updatedColumns() {
        $cols = [];
        foreach ($this->changes() as $c) $cols[] = $c->ColumnName;
        return $cols;
    }
}

$user = new Admin();
$user->UserId = 1;
$snap = new Snapshot($user, ['Age' => 'TheAge']);

$user->Name = "Name1";
$user->Savings = 50.50;
$user->DateOfBirth = new DateTime("2001-01-01");
$user->Age = 21;

$changes = $snap->changes();
$updated = $snap->updatedColumns();

$user2 = new Admin();
$user2->UserId = 1;
$user2->Name = "Name1";
$user2->Savings = 50.50;
$user2->DateOfBirth = new DateTime("2001-01-01");
$user2->Age = 21;
$snap2 = new Snapshot($user2, ['Age' => 'TheAge']);
$user2->Age = 22;

$changes2 = $snap2->changes();
$updated2 = $snap2->updatedColumns();

$user3 = new Admin();
$user3->UserId = 1;
$snap3 = new Snapshot($user3, ['Age' => 'TheAge']);
$changes3 = $snap3->changes();
$updated3 = $snap3->updatedColumns();

echo count($changes), "\n";
echo count($updated), "\n";
echo $changes2[0]->Name, "\n";
echo $changes2[0]->ColumnName, "\n";
echo $changes2[0]->OldValue, "\n";
echo $changes2[0]->NewValue, "\n";
echo count($updated2), "\n";
echo count($changes3), "\n";
echo count($updated3), "\n";
?>
--EXPECT--
