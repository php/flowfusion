--TEST--
Translated seed (inferredbugs-csharp: resin/47)
--FILE--
<?php
class BocModel {}
class SessionFactory {
    public function __construct($config, $model, $loggerFactory) {}
}
class QueryParser {
    public function __construct($sessionFactory, $model) {}
}
class HttpQueryParser {
    public function __construct($queryParser) {}
}
class HttpWriter {
    public function __construct($sessionFactory) {}
}
class HttpReader {
    public function __construct($sessionFactory, $httpParser, $config, $logger) {}
}
class LoggerFactory {
    public function createLogger($name) { return new stdClass(); }
}
class ServiceProvider {
    public function getService($type) { return new LoggerFactory(); }
}
class Services {
    public array $items = [];
    public function addSingleton($type, $value) { $this->items[$type] = $value; }
}

$services = new Services();
$serviceProvider = new ServiceProvider();
$config = new stdClass();

$loggerFactory = $serviceProvider->getService('ILoggerFactory');
$model = new BocModel();
$sessionFactory = new SessionFactory($config, $model, $loggerFactory);
$httpParser = new HttpQueryParser(new QueryParser($sessionFactory, $model));

$services->addSingleton('IStringModel', $model);
$services->addSingleton('ISessionFactory', $sessionFactory);
$services->addSingleton('SessionFactory', $sessionFactory);
$services->addSingleton('HttpQueryParser', $httpParser);
$services->addSingleton('IHttpWriter', new HttpWriter($sessionFactory));
$services->addSingleton('IHttpReader', new HttpReader($sessionFactory, $httpParser, $config, $loggerFactory->createLogger('HttpReader')));
--EXPECT--
