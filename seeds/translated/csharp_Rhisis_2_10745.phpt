--TEST--
Translated seed (inferredbugs-csharp: Rhisis/2)
--FILE--
<?php
class DatabaseConfiguration {
    public string $Database;
    public function __construct(string $database) { $this->Database = $database; }
}

class DatabaseContext {
    public DatabaseConfiguration $configuration;
    public function __construct(DatabaseConfiguration $configuration) { $this->configuration = $configuration; }
    public function migrate(): void {}
    public function databaseExists(): bool { return $this->configuration->Database !== ''; }
}

$path = getenv('DB_CONFIG');
$database = $path === false || $path === '' ? '' : trim($path);
$config = new DatabaseConfiguration($database);
$context = new DatabaseContext($config);

if ($path !== false && $path !== '' && !$context->databaseExists()) {
    throw new InvalidArgumentException("The database '{$config->Database}' doesn't exists.");
}

$context->migrate();
if (!$context->databaseExists()) {
    $config->Database = 'default';
    $context = new DatabaseContext($config);
}

$context->migrate();
--EXPECT--
