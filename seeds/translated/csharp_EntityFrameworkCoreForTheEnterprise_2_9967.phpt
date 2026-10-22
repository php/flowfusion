--TEST--
Translated seed (inferredbugs-csharp: EntityFrameworkCoreForTheEnterprise/2)
--FILE--
<?php

class UserInfo {
    public $name;

    public function __construct($name) {
        $this->name = $name;
    }
}

class StoreDbContext {
    private $connectionString;

    public function __construct($connectionString) {
        $this->connectionString = $connectionString;
        // Simulate database connection logic
    }
}

class HumanResourcesService {
    private $logger;
    private $userInfo;
    private $dbContext;

    public function __construct($logger, $userInfo, $dbContext) {
        $this->logger = $logger;
        $this->userInfo = $userInfo;
        $this->dbContext = $dbContext;
    }
}

class ProductionService {
    private $logger;
    private $userInfo;
    private $dbContext;

    public function __construct($logger, $userInfo, $dbContext) {
        $this->logger = $logger;
        $this->userInfo = $userInfo;
        $this->dbContext = $dbContext;
    }
}

class SalesService {
    private $logger;
    private $userInfo;
    private $dbContext;

    public function __construct($logger, $userInfo, $dbContext) {
        $this->logger = $logger;
        $this->userInfo = $userInfo;
        $this->dbContext = $dbContext;
    }
}

function getHumanResourcesService() {
    $connectionString = "server=(local);database=Store;integrated security=yes;MultipleActiveResultSets=True;";
    return new HumanResourcesService("Logger", new UserInfo("qa"), new StoreDbContext($connectionString));
}

function getProductionService() {
    $connectionString = "server=(local);database=Store;integrated security=yes;MultipleActiveResultSets=True;";
    return new ProductionService("Logger", new UserInfo("qa"), new StoreDbContext($connectionString));
}

function getSalesService() {
    $connectionString = "server=(local);database=Store;integrated security=yes;MultipleActiveResultSets=True;";
    return new SalesService("Logger", new UserInfo("qa"), new StoreDbContext($connectionString));
}

$hrService = getHumanResourcesService();
$productionService = getProductionService();
$salesService = getSalesService();
--EXPECT--
