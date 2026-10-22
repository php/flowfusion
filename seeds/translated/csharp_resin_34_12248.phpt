--TEST--
Translated seed (inferredbugs-csharp: resin/34)
--FILE--
<?php
$services = [];
$config = ['mode' => 'default', 'empty' => '', 'zero' => 0, 'false' => false, 'null' => null];

$httpParser = ['query' => new stdClass()];
$httpBowParser = ['http' => $httpParser];
$sessionFactory = ['config' => $config];

$services['IStringModel'] = new stdClass();
$services['SessionFactory'] = $sessionFactory;
$services['HttpQueryParser'] = $httpParser;
$services['HttpBowQueryParser'] = ['http' => $httpParser];
$services['IQueryFormatter'] = new stdClass();
$services['IWriter'] = ['sessionFactory' => $sessionFactory];
$services['IReader'] = ['sessionFactory' => $sessionFactory, 'httpParser' => $httpParser, 'httpBowParser' => $httpBowParser];

echo "";
?>
--EXPECT--
