--TEST--
Translated seed (inferredbugs-csharp: CoolCat/1)
--FILE--
<?php

class ConnectionStringSetting {
    public $connectionString;

    public function __construct($connectionString) {
        $this->connectionString = $connectionString;
    }
}

class DatabaseConnector {
    private static $errorCount = 0;

    public function tryToConnect(ConnectionStringSetting $siteSettings) {
        while (self::$errorCount < 10) {
            try {
                $pdo = new PDO($siteSettings->connectionString);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "The target database connected.\n";
                return;
            } catch (PDOException $e) {
                echo "The target database hasn't been prepared.\n";
                self::$errorCount++;
            }
            sleep(10);
        }
    }
}

function main() {
    $config = parse_ini_file('config.ini');
    $siteSettings = new ConnectionStringSetting($config['ConnectionStringSetting']);

    $dbConnector = new DatabaseConnector();
    $dbConnector->tryToConnect($siteSettings);
}

main();

?>
--EXPECT--
