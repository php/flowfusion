--TEST--
Translated seed (inferredbugs-csharp: Evolve/1)
--FILE--
<?php

class SQLServerContainer {
    const ExposedPort = "1433";
    const HostPort = "1433";
    const DbName = "master";
    const DbPwd = "Password12!";
    const DbUser = "sa";

    private $container;
    private $disposedValue = false;

    public function getId() {
        return $this->container->getId();
    }

    public function getCnxStr() {
        return "sqlsrv:Server=127.0.0.1;Database=" . self::DbName . ";UID=" . self::DbUser . ";PWD=" . self::DbPwd . ";";
    }

    public function getTimeOutInSec() {
        return 60;
    }

    public function start($fromScratch = false) {
        // Simulating Docker container creation and starting. You would replace this with actual Docker logic.
        $this->container = new class {
            public function start() {
                // Simulate starting a Docker container
                return true;
            }
            public function getId() {
                return uniqid();
            }
            public function dispose() {
                // Simulate disposing of a Docker container
            }
        };

        return $this->container->start();
    }

    protected function dispose($disposing) {
        if (!$this->disposedValue) {
            if ($disposing) {
                $this->container->dispose();
            }
            $this->disposedValue = true;
        }
    }

    public function __destruct() {
        $this->dispose(true);
    }
}

$container = new SQLServerContainer();
$container->start();
--EXPECT--
