--TEST--
Translated seed (inferredbugs-java: Metanome/10)
--FILE--
<?php

class AlgorithmJarLoader {
    protected static $bootstrapClassTagName = "Algorithm-Bootstrap-Class";
    protected $algorithmSubclass;

    public function __construct($algorithmSubclass) {
        $this->algorithmSubclass = $algorithmSubclass;
    }

    public function loadAlgorithm($path) {
        $pathToFolder = dirname(__FILE__) . '/algorithms/';
        $file = $pathToFolder . $path;

        $jar = new \ZipArchive;
        if ($jar->open($file) === TRUE) {
            $manifest = $jar->getFromName('META-INF/MANIFEST.MF');
            preg_match('/' . self::$bootstrapClassTagName . ':\s*(.+)/', $manifest, $matches);
            $className = trim($matches[1]);

            include_once $file; // Simulate loading the class
            $algorithmClass = new ReflectionClass($className);
            return $algorithmClass->newInstance();
        }
    }
}

$loader = new AlgorithmJarLoader('Algorithm');
$loader->loadAlgorithm('my_algorithm.jar');
--EXPECT--
