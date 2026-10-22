--TEST--
Translated seed (inferredbugs-csharp: Evolve/3)
--FILE--
<?php

class MySQLContainer
{
    const EXPOSED_PORT = 3306;
    const HOST_PORT = 3306;
    const DB_NAME = 'my_database';
    public static $dbPwd = 'Password12!';
    const DB_USER = 'root';

    private $container;
    private $disposedValue = false;

    public function getId()
    {
        return $this->container ? $this->container->getId() : null;
    }

    public function getCnxStr()
    {
        return "mysql:host=127.0.0.1;port=" . self::HOST_PORT . ";dbname=" . self::DB_NAME . ";user=" . self::DB_USER . ";password=" . self::$dbPwd;
    }

    public function start($fromScratch = false)
    {
        $this->container = new DockerContainer([
            'image' => 'mariadb',
            'tag' => 'latest',
            'name' => 'mariadb-evolve',
            'env' => [
                'MYSQL_ROOT_PASSWORD=' . self::$dbPwd,
                'MYSQL_DATABASE=' . self::DB_NAME,
            ],
            'exposedPort' => self::EXPOSED_PORT,
            'hostPort' => self::HOST_PORT,
            'removePreviousContainer' => $fromScratch,
        ]);

        return $this->container->start();
    }

    public function createDbConnection()
    {
        return new PDO($this->getCnxStr());
    }

    protected function dispose($disposing)
    {
        if (!$this->disposedValue) {
            if ($disposing) {
                $this->container->dispose();
            }

            $this->disposedValue = true;
        }
    }

    public function __destruct()
    {
        $this->dispose(true);
    }
}

class DockerContainer
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function start()
    {
        // Simulates starting a container
        return true;
    }

    public function getId()
    {
        return uniqid('container_', true);
    }

    public function dispose()
    {
        // Simulates disposing of a container
    }
}

$container = new MySQLContainer();
$container->start();
$connection = $container->createDbConnection();
--EXPECT--
