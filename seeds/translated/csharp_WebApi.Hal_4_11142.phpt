--TEST--
Translated seed (inferredbugs-csharp: WebApi.Hal/4)
--FILE--
<?php
class OrganisationWithPeopleRepresentation {
    public int $id;
    public string $name;
    public array $people;

    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
        $this->people = [
            ['id' => 2, 'name' => 'Alice'],
            ['id' => 3, 'name' => 'Bob'],
        ];
    }
}

$resource = new OrganisationWithPeopleRepresentation(1, 'Org Name');

$json = json_encode($resource, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
if ($json === false) {
    throw new RuntimeException(json_last_error_msg());
}

$xml = new SimpleXMLElement('<organisation/>');
$xml->addChild('id', (string)$resource->id);
$xml->addChild('name', $resource->name);
$people = $xml->addChild('people');
foreach ($resource->people as $person) {
    $p = $people->addChild('person');
    $p->addChild('id', (string)$person['id']);
    $p->addChild('name', $person['name']);
}

echo $json, "\n", $xml->asXML(), "\n";
--EXPECT--
