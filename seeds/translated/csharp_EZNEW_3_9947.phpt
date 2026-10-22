--TEST--
Translated seed (inferredbugs-csharp: EZNEW/3)
--FILE--
<?php

class QueryInfo {
    private array $criterias = []; // all criterias
    private array $orders = []; // order items
    private array $queryFields = []; // specify query fields
    private array $notQueryFields = []; // specify not query fields
    private array $loadPropertys = []; // allow lazy load propertys
    private array $equalCriteriaList = []; // equal criterias
    private int $joinSort = 0;
    
    public function and(string $fieldName, string $operator, $value): self {
        $this->addCriteria('AND', $fieldName, $operator, $value);
        return $this;
    }

    public function or(string $fieldName, string $operator, $value): self {
        $this->addCriteria('OR', $fieldName, $operator, $value);
        return $this;
    }

    private function addCriteria(string $queryOperator, string $fieldName, string $operator, $value): void {
        if (empty($fieldName) || $value === null) {
            return;
        }
        $this->criterias[] = [
            'operator' => $queryOperator,
            'criteria' => [
                'field' => $fieldName,
                'operator' => $operator,
                'value' => $value
            ]
        ];
    }

    public function getQuery(): array {
        return $this->criterias;
    }
}

function main() {
    $query = new QueryInfo();
    $query->and('age', '>', 30)
          ->or('name', '=', 'John');
    
    var_dump($query->getQuery());
}

main();
--EXPECT--
