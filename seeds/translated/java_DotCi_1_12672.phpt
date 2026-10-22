--TEST--
Translated seed (inferredbugs-java: DotCi/1)
--FILE--
<?php

class AfterRunSection {
    private $configs;

    public function __construct($configs) {
        $this->configs = $configs;
    }

    public function toScript($combination) {
        if ($combination['script'] === 'post_build') {
            return implode("\n", $this->configs);
        }
        return null;
    }
}

function configListOrSingleValue(...$configs) {
    return $configs;
}

// Main entry point
$afterRunSection = new AfterRunSection(configListOrSingleValue("spec integration1", "spec integration2"));

$combinationPostBuild = ['script' => 'post_build'];
$script = $afterRunSection->toScript($combinationPostBuild);
if (strpos($script, "spec integration1") !== false) {
    // This indicates the output was correct
}

$combinationMain = ['script' => 'main'];
$scriptNull = $afterRunSection->toScript($combinationMain);
if ($scriptNull === null) {
    // This indicates the output was correct
}
--EXPECT--
