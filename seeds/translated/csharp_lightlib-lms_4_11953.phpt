--TEST--
Translated seed (inferredbugs-csharp: lightlib-lms/4)
--FILE--
<?php
class Patron {
    public int $Id;
    public string $FirstName = '';
    public string $Address = '';
    public string $Telephone = '';
    public string $LastName = '';
}

class PatronIndexModel {
    public array $Patrons;
    public function __construct(array $patrons) { $this->Patrons = $patrons; }
}

class PatronDetailModel {
    public string $FirstName;
    public string $LastName;
    public function __construct(Patron $p) {
        $this->FirstName = $p->FirstName;
        $this->LastName = $p->LastName !== '' ? $p->LastName : 'No Last Name Provided';
    }
}

class ViewResult {
    public mixed $Model;
    public function __construct(mixed $model) { $this->Model = $model; }
}

class PatronController {
    private $service;
    public function __construct($service) { $this->service = $service; }
    public function Index(): ViewResult { return new ViewResult(new PatronIndexModel($this->service->GetAll())); }
    public function Detail(int $id): ViewResult { return new ViewResult(new PatronDetailModel($this->service->Get($id))); }
}

$service = new class {
    public function GetAll(): array {
        $a = new Patron; $a->Id = 888; $a->FirstName = 'Abc Def'; $a->Address = '3 Commerce St'; $a->Telephone = '123';
        $b = new Patron; $b->Id = 213; $b->FirstName = 'Zxy Def'; $b->Address = '2 Commerce St'; $b->Telephone = '23421';
        return [$a, $b];
    }
    public function Get(int $id): Patron {
        $p = new Patron; $p->Id = 888; $p->FirstName = 'Abc Def'; return $p;
    }
};

$c = new PatronController($service);
$r1 = $c->Index();
$r2 = $c->Detail(1);
$r3 = $c->Detail(888);

if (!($r1 instanceof ViewResult) || !($r1->Model instanceof PatronIndexModel) || count($r1->Model->Patrons) !== 2) exit(1);
if (!($r2 instanceof ViewResult) || !($r2->Model instanceof PatronDetailModel) || $r2->Model->FirstName !== 'Abc Def' || $r2->Model->LastName !== 'No Last Name Provided') exit(1);
if (!($r3 instanceof ViewResult) || !($r3->Model instanceof PatronDetailModel)) exit(1);
?>
--EXPECT--
