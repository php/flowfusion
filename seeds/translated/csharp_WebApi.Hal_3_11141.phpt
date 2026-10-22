--TEST--
Translated seed (inferredbugs-csharp: WebApi.Hal/3)
--FILE--
<?php
class OrganisationWithPeopleRepresentation {
    public int $id;
    public string $name;
    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }
}

$resource = new OrganisationWithPeopleRepresentation(1, "Org Name");

$json = json_encode([
    'id' => $resource->id,
    'name' => $resource->name,
    '_links' => [
        'self' => ['href' => '/organisations/' . $resource->id]
    ],
    '_embedded' => [
        'people' => []
    ]
], JSON_UNESCAPED_SLASHES);

$xml = new SimpleXMLElement('<organisation/>');
$xml->addChild('id', (string)$resource->id);
$xml->addChild('name', htmlspecialchars($resource->name, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
$links = $xml->addChild('_links');
$self = $links->addChild('self');
$self->addAttribute('href', '/organisations/' . $resource->id);
$embedded = $xml->addChild('_embedded');
$embedded->addChild('people');

echo $json, "\n", $xml->asXML(), "\n";
?>
--EXPECT--
