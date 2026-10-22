--TEST--
Translated seed (inferredbugs-java: apgdiff/2)
--FILE--
<?php

$command = 'CREATE TRIGGER "my_trigger" BEFORE INSERT OR UPDATE ON "my_table" FOR EACH ROW EXECUTE PROCEDURE my_function;';
$pattern = '/^CREATE\s+TRIGGER\s+"?([^\\s"]+)"?\s+(BEFORE|AFTER)\s+(INSERT|UPDATE|DELETE)(?:\s+OR\s+)?(INSERT|UPDATE|DELETE)?(?:\s+OR\s+)?(INSERT|UPDATE|DELETE)?\s+ON\s+"?([^\\s"]+)"?(?:\s+FOR\s+)?(?:EACH\s+)?(ROW|STATEMENT)?\s+EXECUTE\s+PROCEDURE\s+([^;]+);$/i';

$matches = [];
if (preg_match($pattern, trim($command), $matches)) {
    $triggerName = trim($matches[1]);
    $when = $matches[2];
    $events = [$matches[3], $matches[4], $matches[5]];
    $tableName = trim($matches[6]);
    $fireOn = $matches[7];
    $procedure = trim($matches[8]);

    $trigger = [];
    $trigger['before'] = strtolower($when) === 'before';
    $trigger['for_each_row'] = isset($fireOn) && strtolower($fireOn) === 'row';
    $trigger['function'] = $procedure;
    $trigger['name'] = $triggerName;
    $trigger['on_delete'] = in_array('DELETE', $events, true);
    $trigger['on_insert'] = in_array('INSERT', $events, true);
    $trigger['on_update'] = in_array('UPDATE', $events, true);
    $trigger['table_name'] = $tableName;

    // Simulates adding the trigger to a database (not implemented)
} else {
    echo "CANNOT PARSE COMMAND: $command\n";
}
--EXPECT--
