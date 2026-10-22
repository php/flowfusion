--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/8)
--FILE--
<?php

class Rule {
    protected $ruleFolder;
    protected $configFileName;
    protected $evoConfig;
    private $isActive = true;

    public function __construct($evoConfig, $configFileName) {
        $this->configFileName = $configFileName;
        $this->evoConfig = $evoConfig;
    }

    public function getRuleFolder() {
        return $this->ruleFolder;
    }

    public function isActive() {
        return $this->isActive;
    }

    public function report() {
        return "";
    }

    protected function writeConfig($c) {
        $f = $this->evoConfig['analyzerConfigFolder'] . $this->configFileName;
        if (file_exists($f)) {
            error_log("Config File already exists, not writing new Config: " . $this->configFileName);
        } else {
            file_put_contents($f, serialize($c));
        }
    }

    protected function prepareConfigOutputFolder() {
        $f = $this->evoConfig['outputFolder'] . $this->getConfig()['outputFolder'];
        if ($this->evoConfig['cleanStart']) {
            if (is_dir($f)) {
                array_map('unlink', glob("$f/*"));
            }
        }
        mkdir($f, 0777, true);
    }
}

$config = ['analyzerConfigFolder' => '/path/to/config/', 'outputFolder' => '/path/to/output/', 'cleanStart' => true];
$rule = new Rule($config, 'config.xml');
$rule->prepareConfigOutputFolder();
$rule->writeConfig(['setting1' => 'value1']);
--EXPECT--
