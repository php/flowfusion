--TEST--
Translated seed (inferredbugs-csharp: Hangfire/14)
--FILE--
<?php
declare(strict_types=1);

class SqlServerStorageOptions {
    public int $PollInterval = 15;
}

class SqlServerStorage {
    private string $connectionString;
    private SqlServerStorageOptions $options;

    public function __construct(string $connectionString, ?SqlServerStorageOptions $options = null) {
        if ($connectionString === '') {
            throw new InvalidArgumentException('connectionString');
        }
        $this->connectionString = $connectionString;
        $this->options = $options ?? new SqlServerStorageOptions();
    }

    public function createMonitoring(): object {
        return (object)['connection' => $this->connectionString];
    }

    public function createConnection(): object {
        return $this->createPooledConnection();
    }

    public function createPooledConnection(): object {
        return (object)['connection' => $this->connectionString];
    }

    public function getComponents(): iterable {
        yield (object)['type' => 'SchedulePoller', 'connection' => $this->createConnection(), 'interval' => $this->options->PollInterval];
        yield (object)['type' => 'ServerWatchdog', 'connection' => $this->createConnection()];
        yield (object)['type' => 'ExpirationManager', 'connection' => (object)['connection' => $this->connectionString]];
    }
}

$storage = new SqlServerStorage('Server=.;Database=Test;Trusted_Connection=True;');
foreach ($storage->getComponents() as $component) {
    $component->type;
}
?>
--EXPECT--
