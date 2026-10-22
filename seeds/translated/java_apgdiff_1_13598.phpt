--TEST--
Translated seed (inferredbugs-java: apgdiff/1)
--FILE--
<?php

$command = 'CREATE TABLE "users" (id SERIAL PRIMARY KEY, name VARCHAR(100) CONSTRAINT user_name_unique UNIQUE, age INT)';
$line = $command;

if (preg_match('/CREATE[\\s]+TABLE[\\s]+\"?([^\\s\"]+)\"?[\\s]*\\(/i', $line, $tableNameMatch)) {
    $tableName = trim($tableNameMatch[1]);
    $line = preg_replace('/CREATE[\\s]+TABLE[\\s]+\"?[^\\s\"]+\"?[\\s]*\\(/i', '', $line);
} else {
    die("Cannot parse command: " . $line);
}

$table = new StdClass(); 
$table->name = $tableName;
$table->columns = [];
$table->constraints = [];

$line = rtrim($line, ');');
$lines = explode(',', $line);

foreach ($lines as $line) {
    $line = trim($line);

    if (preg_match('/CONSTRAINT[\\s]+\"?([^\\s\"]+)\"?[\\s]+(.*)/i', $line, $constraintMatch)) {
        $constraint = new StdClass();
        $constraint->name = trim($constraintMatch[1]);
        $constraint->definition = trim($constraintMatch[2]);
        $table->constraints[] = $constraint;
    } elseif (preg_match('/\"?([^\\s\"]+)\"?[\\s]+(.*)/i', $line, $columnMatch)) {
        $column = new StdClass();
        $column->name = trim($columnMatch[1]);
        $column->definition = trim($columnMatch[2]);
        $table->columns[] = $column;
    } else {
        die("Cannot parse column definition: " . $line);
    }
}

if (empty($table->columns) && empty($table->constraints)) {
    die("Cannot parse CREATE TABLE '" . $table->name . "' - no valid definitions found.");
}
--EXPECT--
