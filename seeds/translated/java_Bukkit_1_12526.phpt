--TEST--
Translated seed (inferredbugs-java: Bukkit/1)
--FILE--
<?php

class InvalidPluginException extends Exception {}
class UnknownDependencyException extends Exception {}
class PluginDescriptionFile {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}

class JavaPluginLoader {
    private $loaders = [];

    public function loadPlugin($file) {
        if (!file_exists($file)) {
            throw new InvalidPluginException("$file does not exist");
        }

        $description = new PluginDescriptionFile(pathinfo($file, PATHINFO_FILENAME));

        $dataFolder = dirname($file) . '/' . $description->getName();

        if (file_exists($dataFolder) && !is_dir($dataFolder)) {
            throw new InvalidPluginException("$dataFolder exists and is not a directory");
        }

        $depend = []; // Simulating dependencies

        foreach ($depend as $pluginName) {
            if (!isset($this->loaders[$pluginName])) {
                throw new UnknownDependencyException($pluginName);
            }
        }

        // Simulating loading the plugin
        $this->loaders[$description->getName()] = $file; // Storing the plugin

        return $description;
    }

    public function getClassByName($name) {
        return isset($this->loaders[$name]) ? $this->loaders[$name] : null;
    }
}

$loader = new JavaPluginLoader();
$loader->loadPlugin('example_plugin_file.php');
--EXPECT--
