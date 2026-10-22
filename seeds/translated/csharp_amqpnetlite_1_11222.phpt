--TEST--
Translated seed (inferredbugs-csharp: amqpnetlite/1)
--FILE--
<?php
class Person { public $EyeColor; public $Height; public $Weight; }
class Student extends Person { public $GPA; public $Address; }
class Teacher extends Person { public $Department; public $Classes; }
class ListAddress { public $Street; public $City; public $State; public $Zip; }
class MapAddress { public $Street; public $City; public $State; public $Zip; }
class InternationalAddress { public $Address; public $Country; }

$person = new Person();
$person->EyeColor = "brown";
$person->Height = 175;
$person->Weight = 75;
$student = new Student();
$student->GPA = 4.8;
$student->Address = (function () {
    $a = new ListAddress();
    $a->Street = "123 Main St.";
    $a->City = "Big Apple";
    $a->State = "NY";
    $a->Zip = "12345";
    return $a;
})();
$teacher = new Teacher();
$teacher->Department = "Computer Science";
$teacher->Classes = ["CS101", "CS106", "CS210"];
$address = new InternationalAddress();
$address->Address = (function () {
    $a = new MapAddress();
    $a->Street = "123 Main St.";
    $a->City = "Big Apple";
    $a->State = "NY";
    $a->Zip = "12345";
    return $a;
})();
$address->Country = "usa";

$described = [
    "descriptor" => "samples.amqpnetlite:student",
    "value" => [80, 6, "black", 4.9, ["descriptor" => "PeerToPeer.CustomType.ListAddress", "value" => ["123 Main St.", "Big Apple", "NY", "12345"]]]
];

$map = ["street" => "123 Main St.", "city" => "Big Apple", "state" => "NY", "zip" => "12345"];
$map2 = ["address" => ["street" => "123 Main St.", "city" => "Big Apple", "state" => "NY", "zip" => "12345"], "country" => "usa"];

foreach ([$person, $student, $teacher, $address, $described, $map, $map2] as $v) {
    serialize($v);
}
?>
--EXPECT--
