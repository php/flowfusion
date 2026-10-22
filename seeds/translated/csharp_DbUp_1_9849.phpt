--TEST--
Translated seed (inferredbugs-csharp: DbUp/1)
--FILE--
<?php

class GivenAnUptoDateDatabase {
    private $allScripts;
    private $executedScripts;

    public function __construct() {
        $this->executedScripts = ["0001.sql", "0002.sql", "0004.sql"];
        $this->allScripts = [
            "0001.sql" => "",
            "0004.sql" => "",
            "0002.sql" => ""
        ];
    }

    public function getExecutedScripts() {
        return $this->executedScripts;
    }

    public function getAllScripts() {
        return $this->allScripts;
    }
}

function main() {
    $db = new GivenAnUptoDateDatabase();
    $executedScripts = $db->getExecutedScripts();
    $allScripts = $db->getAllScripts();
}

main();
--EXPECT--
