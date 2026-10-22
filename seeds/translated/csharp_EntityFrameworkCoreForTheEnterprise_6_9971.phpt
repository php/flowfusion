--TEST--
Translated seed (inferredbugs-csharp: EntityFrameworkCoreForTheEnterprise/6)
--FILE--
<?php

class Logger {
    public function log($message) {
        // Placeholder for logging logic
    }
}

class UserInfo {
    private $username;

    public function __construct($username) {
        $this->username = $username;
    }
}

class DbContext {
    // Simulating a database context
}

class ProductionService {
    private $logger;
    private $userInfo;
    private $dbContext;

    public function __construct(Logger $logger, UserInfo $userInfo, DbContext $dbContext) {
        $this->logger = $logger;
        $this->userInfo = $userInfo;
        $this->dbContext = $dbContext;
    }
}

class SalesService {
    private $logger;
    private $userInfo;
    private $dbContext;

    public function __construct(Logger $logger, UserInfo $userInfo, DbContext $dbContext) {
        $this->logger = $logger;
        $this->userInfo = $userInfo;
        $this->dbContext = $dbContext;
    }
}

class ServiceMocker {
    public static function getProductionService() {
        return new ProductionService(new Logger(), new UserInfo("mocker"), new DbContext());
    }

    public static function getSalesService() {
        return new SalesService(new Logger(), new UserInfo("mocker"), new DbContext());
    }
}

function main() {
    $productionService = ServiceMocker::getProductionService();
    $salesService = ServiceMocker::getSalesService();
}

main();
--EXPECT--
