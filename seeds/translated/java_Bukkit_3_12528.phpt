--TEST--
Translated seed (inferredbugs-java: Bukkit/3)
--FILE--
<?php

class Plugin {
    private $isEnabled = false;
    private $initialized = false;
    private $dataFolder = null;
    private $configFile = null;

    public function __construct() {
        $this->dataFolder = __DIR__ . '/data';
        $this->configFile = $this->dataFolder . '/config.yml';
    }

    public function getDataFolder() {
        return $this->dataFolder;
    }

    public function isEnabled() {
        return $this->isEnabled;
    }

    protected function setEnabled($enabled) {
        if ($this->isEnabled !== $enabled) {
            $this->isEnabled = $enabled;

            if ($this->isEnabled) {
                $this->onEnable();
            } else {
                $this->onDisable();
            }
        }
    }

    public function initialize() {
        if (!$this->initialized) {
            $this->initialized = true;
            if (!file_exists($this->dataFolder)) {
                mkdir($this->dataFolder, 0777, true);
            }
            $this->reloadConfig();
        }
    }

    public function reloadConfig() {
        if (!file_exists($this->configFile)) {
            file_put_contents($this->configFile, "default: value");
        }
    }

    public function saveConfig() {
        file_put_contents($this->configFile, "saved: value");
    }

    public function onEnable() {
        echo "Plugin enabled\n";
    }

    public function onDisable() {
        echo "Plugin disabled\n";
    }
}

$plugin = new Plugin();
$plugin->initialize();
$plugin->setEnabled(true);
$plugin->saveConfig();
$plugin->setEnabled(false);
--EXPECT--
