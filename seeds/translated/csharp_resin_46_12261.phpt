--TEST--
Translated seed (inferredbugs-csharp: resin/46)
--FILE--
<?php

class BocModel {}
class SessionFactory {
    public function __construct($config, $model, $loggerFactory) {}
}
class HttpQueryParser {
    public function __construct($sessionFactory, $model) {}
}
class HttpWriter {
    public function __construct($sessionFactory) {}
}
class HttpReader {
    public function __construct($sessionFactory, $httpParser, $config) {}
}

$config = (object)[];
$loggerFactory = null;
$model = new BocModel();
$sessionFactory = new SessionFactory($config, $model, $loggerFactory);
$httpParser = new HttpQueryParser($sessionFactory, $model);

$services = [];
$services['IStringModel'] = $model;
$services['ISessionFactory'] = $sessionFactory;
$services['SessionFactory'] = $sessionFactory;
$services['HttpQueryParser'] = $httpParser;
$services['IHttpWriter'] = new HttpWriter($sessionFactory);
$services['IHttpReader'] = new HttpReader($sessionFactory, $httpParser, $config);

?>
--EXPECT--
