--TEST--
Translated seed (inferredbugs-csharp: EntityFrameworkCoreForTheEnterprise/5)
--FILE--
<?php

class UserInfo {
    public function __construct($username) {
        $this->username = $username;
    }
}

class LogHelper {
    public static function getLogger($class) {
        // Simulate a logger object
        return "Logger for " . $class;
    }
}

class DbContextMocker {
    public static function getStoreDbContext() {
        // Simulate a database context
        return "MockedDbContext";
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

function getProductionService() {
    return new ProductionService(LogHelper::getLogger("ProductionService"), new UserInfo("mocker"), DbContextMocker::getStoreDbContext());
}

function getSalesService() {
    return new SalesService(LogHelper::getLogger("SalesService"), new UserInfo("mocker"), DbContextMocker::getStoreDbContext());
}

$productionService = getProductionService();
$salesService = getSalesService();
--EXPECT--
