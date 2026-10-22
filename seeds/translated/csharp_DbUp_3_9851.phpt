--TEST--
Translated seed (inferredbugs-csharp: DbUp/3)
--FILE--
<?php

class GivenANewDatabase {
    private $allScripts = [];
    private $scriptProvider;
    private $versionTracker;
    private $connectionString;
    private $log;

    public function __construct($scriptProvider, $versionTracker, $connectionString, $log) {
        $this->scriptProvider = $scriptProvider;
        $this->versionTracker = $versionTracker;
        $this->connectionString = $connectionString;
        $this->log = $log;
    }

    public function beforeEach() {
        $this->allScripts = [
            ['name' => "0001.sql", 'content' => ""],
            ['name' => "0004.sql", 'content' => ""],
            ['name' => "0002.sql", 'content' => ""]
        ];

        $this->scriptProvider->setScripts($this->allScripts);
        $this->versionTracker->setExecutedScripts($this->connectionString, $this->log, []);
    }
}

class ScriptProvider {
    private $scripts = [];

    public function setScripts($scripts) {
        $this->scripts = $scripts;
    }

    public function getScripts() {
        return $this->scripts;
    }
}

class VersionTracker {
    private $executedScripts = [];

    public function setExecutedScripts($connectionString, $log, $executedScripts) {
        $this->executedScripts[$connectionString] = $executedScripts;
    }

    public function getExecutedScripts($connectionString, $log) {
        return $this->executedScripts[$connectionString] ?? [];
    }
}

function main() {
    $scriptProvider = new ScriptProvider();
    $versionTracker = new VersionTracker();
    $database = new GivenANewDatabase($scriptProvider, $versionTracker, 'some_connection_string', 'some_log');
    
    $database->beforeEach();
}

main();
--EXPECT--
