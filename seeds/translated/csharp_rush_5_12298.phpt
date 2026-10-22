--TEST--
Translated seed (inferredbugs-csharp: rush/5)
--FILE--
<?php
class Ruleset {
    public string $Description;
    public function __construct(string $description) {
        $this->Description = $description;
    }
}

class RushRulesetConfigManager {
    private array $values;
    public function __construct(array $values) {
        $this->values = $values;
    }
    public function getBindable(string $key): mixed {
        return $this->values[$key] ?? null;
    }
}

$ruleset = new Ruleset("Rush");
$config = new RushRulesetConfigManager(["AutomaticFever" => true]);

$header = $ruleset->Description;
$children = [
    [
        "LabelText" => "Automatic Fever activation",
        "Current" => $config->getBindable("AutomaticFever"),
    ],
];

echo $header, "\n";
var_export($children);
echo "\n";
--EXPECT--
