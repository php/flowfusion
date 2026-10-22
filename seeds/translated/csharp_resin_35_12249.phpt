--TEST--
Translated seed (inferredbugs-csharp: resin/35)
--FILE--
<?php
class Container {
    public array $services = [];
    public function addSingleton(string $type, $instance): void {
        $this->services[$type] = $instance;
    }
}

class QueryParser {}
class HttpQueryParser {
    public function __construct(public QueryParser $parser) {}
}
class HttpBowQueryParser {
    public function __construct(public HttpQueryParser $parser) {}
}
class BocModel {}
class SessionFactory {
    public bool $begun = false;
    public function __construct($config, public BocModel $model) {}
    public function beginInit(): void { $this->begun = true; }
}
class QueryFormatter {}
class StoreWriter {
    public function __construct(public SessionFactory $factory) {}
}
class StoreReader {
    public function __construct(public SessionFactory $factory, public HttpQueryParser $httpParser, public HttpBowQueryParser $httpBowParser) {}
}

$services = new Container();
$config = new stdClass();

$httpParser = new HttpQueryParser(new QueryParser());
$httpBowParser = new HttpBowQueryParser($httpParser);
$sessionFactory = new SessionFactory($config, new BocModel());

$sessionFactory->beginInit();

$services->addSingleton(BocModel::class, new BocModel());
$services->addSingleton(SessionFactory::class, $sessionFactory);
$services->addSingleton(HttpQueryParser::class, $httpParser);
$services->addSingleton(HttpBowQueryParser::class, new HttpBowQueryParser($httpParser));
$services->addSingleton(QueryFormatter::class, new QueryFormatter());
$services->addSingleton(StoreWriter::class, new StoreWriter($sessionFactory));
$services->addSingleton(StoreReader::class, new StoreReader($sessionFactory, $httpParser, $httpBowParser));
?>
--EXPECT--
