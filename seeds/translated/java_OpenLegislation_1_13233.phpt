--TEST--
Translated seed (inferredbugs-java: OpenLegislation/1)
--FILE--
<?php

$stdin = fopen("php://stdin", "r");

echo "openlegLuceneConsole> ";
while (($line = fgets($stdin)) !== false) {
    $line = trim($line);
    if ($line === "quit") {
        break;
    } elseif (strpos($line, "index ") === 0) {
        $cmd = substr($line, strpos($line, " ") + 1);
        $tokens = preg_split('/\s+/', $cmd);
        $iType = $tokens[0];
        $start = 1;
        $max = 1000000;
        $pageSize = 10;

        if (isset($tokens[1])) {
            $start = (int)$tokens[1];
        }
        if (isset($tokens[2])) {
            $max = (int)$tokens[2];
        }
        if (isset($tokens[3])) {
            $pageSize = (int)$tokens[3];
        }

        // Simulate the indexSenateData function
    } elseif (strpos($line, "optimize") === 0) {
        // Simulate the optimize function
    } elseif (strpos($line, "delete ") === 0) {
        $cmd = substr($line, strpos($line, " ") + 1);
        $tokens = preg_split('/\s+/', $cmd);
        $type = $tokens[0];
        $id = $tokens[1];
        // Simulate the deleteSenateObjectById function
    } elseif (strpos($line, "create") === 0) {
        // Simulate the createIndex function
    } else {
        // Simulate the search function
    }
    echo "openleg search > ";
}

fclose($stdin);
echo "Exiting Search Engine\n";
--EXPECT--
