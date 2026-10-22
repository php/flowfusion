--TEST--
Translated seed (inferredbugs-java: HikariCP/5)
--FILE--
<?php

class StubStatement {}

class FastStatementList {
    private $statements = [];

    public function add(StubStatement $statement) {
        $this->statements[] = $statement;
    }

    public function get($index) {
        return $this->statements[$index] ?? null;
    }
}

$list = new FastStatementList();
for ($i = 0; $i < 100; $i++) {
    $list->add(new StubStatement());
}

for ($i = 0; $i < 100; $i++) {
    $element = $list->get($i);
}
--EXPECT--
