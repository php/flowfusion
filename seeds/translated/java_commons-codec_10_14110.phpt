--TEST--
Translated seed (inferredbugs-java: commons-codec/10)
--FILE--
<?php
class BeiderMorseEncoder {
    private $nameType;
    private $ruleType;
    private $concat = true;

    public function setNameType($type) {
        $this->nameType = $type;
    }

    public function setRuleType($type) {
        if ($type == 'RULES') {
            throw new InvalidArgumentException();
        }
        $this->ruleType = $type;
    }

    public function setConcat($value) {
        $this->concat = $value;
    }

    public function isConcat() {
        return $this->concat;
    }

    public function encode($name) {
        return $name !== "" ? "encoded_" . $name : ""; 
    }
}

$encoder = new BeiderMorseEncoder();
$encoder->setNameType("GENERIC");
$encoder->setRuleType("APPROX");
$names = ["ácz", "átz", "Ignácz", "Ignátz", "Ignác"];
foreach ($names as $name) {
    $encoder->encode($name);
}

$encoder->setConcat(false);
$encoder->setNameType("ASHKENAZI");
$encoder->setRuleType("EXACT");

$testName = "MacGhilleseatheanaich";
$encoder->encode($testName);

$rule = new class {
    public function patternAndContextMatches($string, $index) {
        if ($index < 0) {
            throw new OutOfBoundsException();
        }
    }
};

$rule->patternAndContextMatches("bob", -1);

$chars = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'o', 'u'];
$stringBuffer = "";
$randKeys = array_rand($chars, 40);
foreach ($randKeys as $key) {
    $stringBuffer .= $chars[$key];
    $encoder->encode($stringBuffer);
}
?>
--EXPECT--
