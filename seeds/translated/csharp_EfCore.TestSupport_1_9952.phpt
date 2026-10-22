--TEST--
Translated seed (inferredbugs-csharp: EfCore.TestSupport/1)
--FILE--
<?php

function dropSchema($pdo) {
    $sql = "
    DO $$
    DECLARE
        r RECORD;
    BEGIN
        FOR r IN (SELECT nspname FROM pg_namespace WHERE nspname NOT IN ('pg_toast', 'pg_catalog', 'information_schema'))
            LOOP
                EXECUTE 'DROP SCHEMA ' || quote_ident(r.nspname) || ' CASCADE';
            END LOOP;
        EXECUTE 'CREATE SCHEMA public; GRANT ALL ON SCHEMA public TO postgres; GRANT ALL ON SCHEMA public TO public';
    END $$";

    $pdo->exec($sql);
}

function databaseExists($pdo, $database) {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $database");
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM pg_catalog.pg_database WHERE datname='$database'");
    return $stmt->fetchColumn() == 1;
}

function fasterPostgreSqlEnsureClean($pdo, $setUpSchema = true) {
    $connectionString = $pdo->getAttribute(PDO::ATTR_CONNECTION_STRING);
    
    if (databaseExists($pdo, parse_url($connectionString, PHP_URL_PATH))) {
        dropSchema($pdo);
    }

    if ($setUpSchema) {
        // Code to ensure schema is set up can be added here if needed
    }
}

try {
    $dsn = 'pgsql:host=localhost;dbname=my_database';
    $pdo = new PDO($dsn, 'username', 'password');
    fasterPostgreSqlEnsureClean($pdo);
} catch (Exception $e) {
    // Handle exception
}
--EXPECT--
