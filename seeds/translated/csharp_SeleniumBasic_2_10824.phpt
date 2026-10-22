--TEST--
Translated seed (inferredbugs-csharp: SeleniumBasic/2)
--FILE--
<?php
class WebElement {
    public string $id;
    function __construct(string $id) { $this->id = $id; }
    function serializeJson(): array { return ["ELEMENT" => $this->id]; }
    function equals(WebElement $other): bool { return $this->id === $other->id || $this->serializeJson()["ELEMENT"] === $other->serializeJson()["ELEMENT"]; }
    function formatArguments($arguments): array {
        if ($arguments === null) return [$this, []];
        if (is_array($arguments)) return [$this, $arguments];
        return [$this, [$arguments]];
    }
    function executeScript(string $script, $arguments = null) {
        $args = $this->formatArguments($arguments);
        return [$script, $args[0]->id, $args[1]];
    }
}

$e = new WebElement("abc");
$u = new WebElement("abc");
$v = new WebElement("xyz");

$e->serializeJson();
$e->equals($u);
$e->equals($v);
$e->executeScript("return 1;", null);
$e->executeScript("return 2;", "x");
$e->executeScript("return 3;", [1, 2, 3]);
echo "ok\n";
?>
--EXPECT--
