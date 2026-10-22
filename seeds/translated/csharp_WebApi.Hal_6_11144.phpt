--TEST--
Translated seed (inferredbugs-csharp: WebApi.Hal/6)
--FILE--
<?php

$resource = ["id" => 1, "name" => "Org Name"];
$variants = [
    $resource,
    ["id" => 1, "name" => "Org Name", "_links" => ["self" => ["href" => "/orgs/1"]]],
    ["id" => 1, "name" => "Org Name", "_links" => ["self" => ["title" => "Org Name"]]],
    ["id" => 1, "name" => "Org Name", "_links" => ["self" => null]],
];

foreach ($variants as $v) {
    echo json_encode($v, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
}

$xml = new SimpleXMLElement('<resource/>');
$xml->addChild('id', '1');
$xml->addChild('name', 'Org Name');
$links = $xml->addChild('_links');
$self = $links->addChild('self');
$self->addAttribute('href', '/orgs/1');
echo $xml->asXML() . "\n";

?>
--EXPECT--
