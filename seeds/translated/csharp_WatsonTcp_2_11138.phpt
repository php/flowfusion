--TEST--
Translated seed (inferredbugs-csharp: WatsonTcp/2)
--FILE--
<?php
class Person {
    public string $FirstName;
    public string $LastName;
    public int $Number;
    public function __construct(string $first = '', string $last = '', int $num = 0) {
        $this->FirstName = $first;
        $this->LastName = $last;
        $this->Number = $num;
    }
}

for ($i = 0; $i < 10; $i++) {
    $p = new Person('hello', 'world', $i);
    $md = ['person' => $p];
    $json = json_encode($md, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo $json, PHP_EOL;
    $decoded = json_decode($json, true);
    $person = $decoded['person'] ?? null;
    if (is_array($person)) {
        echo $person['FirstName'] . ' ' . $person['LastName'] . ': ' . $person['Number'], PHP_EOL;
    }
}
?>
--EXPECT--
