--TEST--
Translated seed (inferredbugs-csharp: DbUp/2)
--FILE--
<?php

class GivenAnOutOfDateDatabase {
    private $allScripts;
    private $executedScripts;

    public function __construct() {
        $this->beforeEach();
    }

    public function beforeEach() {
        $this->allScripts = [
            ["name" => "0001.southwind.sql", "content" => "--LITTLE BOBBY DROP TABLES WAS HERE."],
            ["name" => "0002.southwind.sql", "content" => "CREATE TABLE USERS --AGAIN"]
        ];

        $this->executedScripts = ["0001.southwind.sql"];
        $this->runScripts();
    }

    public function runScripts() {
        foreach ($this->allScripts as $script) {
            if (!in_array($script["name"], $this->executedScripts)) {
                // Simulate executing the script
                echo "Executing script: " . $script["name"] . "\n";
            }
        }
    }
}

new GivenAnOutOfDateDatabase();
--EXPECT--
