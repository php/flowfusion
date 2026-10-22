--TEST--
Translated seed (inferredbugs-java: Spigot-API/5)
--FILE--
<?php

class FileConfiguration {
    protected $options;

    public function save($file) {
        if ($file === null) throw new InvalidArgumentException("File cannot be null");

        $data = $this->saveToString();
        file_put_contents($file, $data);
    }

    public function load($file) {
        if ($file === null) throw new InvalidArgumentException("File cannot be null");

        if (!file_exists($file)) throw new FileNotFoundException("File not found");

        $contents = file_get_contents($file);
        $this->loadFromString($contents);
    }

    public function saveToString() {
        return "configuration data"; // Placeholder data
    }

    public function loadFromString($contents) {
        if ($contents === null) throw new InvalidArgumentException("Contents cannot be null");
        // Simulate loading data
    }

    public function options() {
        if ($this->options === null) {
            $this->options = new FileConfigurationOptions($this);
        }

        return $this->options;
    }
}

class FileConfigurationOptions {
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }
}

class FileNotFoundException extends Exception {}

$config = new FileConfiguration();
$config->save('config.txt');
$config->load('config.txt');
--EXPECT--
