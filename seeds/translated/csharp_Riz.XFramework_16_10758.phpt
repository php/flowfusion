--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/16)
--FILE--
<?php
class XFrameworkException extends Exception {}

class Builder {
    public string $sql = '';
    function appendMember(string $alias, string $name): void { $this->sql .= $alias . '.' . $name; }
    function append(string $s): void { $this->sql .= $s; }
    function appendNewLine(): void { $this->sql .= "\n"; }
}

class UpdateExpressionVisitor {
    private Builder $builder;
    function __construct() { $this->builder = new Builder(); }
    function visit(array $node): string {
        if (!isset($node['bindings']) || count($node['bindings']) === 0) throw new XFrameworkException('Update<T> at least update one member.');
        foreach ($node['bindings'] as $i => $member) {
            $this->builder->appendMember('t0', $member['name']);
            $this->builder->append(' = ');
            $this->builder->append((string)$member['value']);
            if ($i < count($node['bindings']) - 1) { $this->builder->append(','); $this->builder->appendNewLine(); }
        }
        return $this->builder->sql;
    }
}

$visitor = new UpdateExpressionVisitor();
$visitor->visit(['bindings' => [['name' => 'Id', 'value' => 1], ['name' => 'Name', 'value' => 'x']]]);
echo $visitor->visit(['bindings' => [['name' => 'Age', 'value' => 42]]]);
?>
--EXPECT--
