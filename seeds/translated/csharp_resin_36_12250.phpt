--TEST--
Translated seed (inferredbugs-csharp: resin/36)
--FILE--
<?php
class BocModel {}
class QueryParser {}
class HttpQueryParser {
    public function __construct($p) {}
}
class HttpBowQueryParser {
    public function __construct($p) {}
}
class SessionFactory {
    public function __construct($config, $model) {}
}
class QueryFormatter {}
class StoreWriter {
    public function __construct($sessionFactory) {}
}
class StoreReader {
    public function __construct($sessionFactory, $httpParser, $httpBowParser) {}
}

$services = [];
$config = new stdClass();
$model = new BocModel();
$httpParser = new HttpQueryParser(new QueryParser());
$httpBowParser = new HttpBowQueryParser($httpParser);
$sessionFactory = new SessionFactory($config, $model);

$services['IStringModel'] = $model;
$services['SessionFactory'] = $sessionFactory;
$services['HttpQueryParser'] = $httpParser;
$services['HttpBowQueryParser'] = new HttpBowQueryParser($httpParser);
$services['IQueryFormatter'] = new QueryFormatter();
$services['IWriter'] = new StoreWriter($sessionFactory);
$services['IReader'] = new StoreReader($sessionFactory, $httpParser, $httpBowParser);
--EXPECT--
