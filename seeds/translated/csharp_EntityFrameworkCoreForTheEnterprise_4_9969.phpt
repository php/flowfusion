--TEST--
Translated seed (inferredbugs-csharp: EntityFrameworkCoreForTheEnterprise/4)
--FILE--
<?php

class UserInfo {
    public $name;
}

class StoreDbContext {
    public function __construct($options) {
        // Mimicking some database connection behavior
    }
}

class HumanResourcesService {
    public function __construct($logger, $userInfo, $dbContext) {
        // Initialization logic
    }
}

class ProductionService {
    public function __construct($logger, $userInfo, $dbContext) {
        // Initialization logic
    }
}

class SalesService {
    public function __construct($logger, $userInfo, $dbContext) {
        // Initialization logic
    }
}

class Logger {
    public static function getLogger($className) {
        // Mock logger instance
        return "Logger for {$className}";
    }
}

function getHumanResourcesService() {
    $connectionString = "server=localhost;dbname=Store;charset=utf8mb4";
    $dbContext = new StoreDbContext($connectionString);
    return new HumanResourcesService(Logger::getLogger(HumanResourcesService::class), new UserInfo(), $dbContext);
}

function getProductionService() {
    $connectionString = "server=localhost;dbname=Store;charset=utf8mb4";
    $dbContext = new StoreDbContext($connectionString);
    return new ProductionService(Logger::getLogger(ProductionService::class), new UserInfo(), $dbContext);
}

function getSalesService() {
    $connectionString = "server=localhost;dbname=Store;charset=utf8mb4";
    $dbContext = new StoreDbContext($connectionString);
    return new SalesService(Logger::getLogger(SalesService::class), new UserInfo(), $dbContext);
}

$hrService = getHumanResourcesService();
$productionService = getProductionService();
$salesService = getSalesService();

?>
--EXPECT--
