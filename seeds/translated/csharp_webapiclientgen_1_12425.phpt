--TEST--
Translated seed (inferredbugs-csharp: webapiclientgen/1)
--FILE--
<?php
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_FIELD)]
class DataMember {
    public function __construct(public ?string $name = null) {}
}

class BaseModel {
    #[DataMember]
    public int $baseId = 1;
}

class ChildModel extends BaseModel {
    #[DataMember("renamed")]
    public ?string $name = null;
    #[DataMember]
    public array $items = [];
    #[DataMember]
    public ?ChildModel $self = null;
    #[DataMember]
    public array $matrix = [];
    public int $ignored = 0;
}

enum Status: int {
    case Zero = 0;
    case One = 1;
    case Two = 2;
}

$types = [BaseModel::class, ChildModel::class, Status::class];
$pending = array_flip($types);
$out = [];

foreach ($types as $type) {
    $rc = new ReflectionClass($type);
    $ns = str_replace('.', '_', ($rc->getNamespaceName() ?: '') . '.Client');
    $decl = ['name' => $rc->getShortName(), 'ns' => $ns, 'members' => []];

    if ($rc->isEnum()) {
        $k = 0;
        foreach ($rc->getCases() as $case) {
            $v = $case->getBackingValue();
            $decl['members'][] = [$case->getName(), $v !== $k ? $v : null];
            $k++;
        }
    } elseif ($rc->isClass() || $rc->isInterface()) {
        if (!$rc->isInterface()) {
            $parent = $rc->getParentClass();
            if ($parent) {
                $decl['base'] = isset($pending[$parent->getName()]) ? str_replace('.', '_', ($parent->getNamespaceName() ?: '') . '.Client') . '.' . $parent->getShortName() : $parent->getName();
            }
        }

        foreach ($rc->getProperties() as $p) {
            $attrs = $p->getAttributes(DataMember::class);
            if (!$attrs) continue;
            $a = $attrs[0]->newInstance();
            $name = $a->name ?? $p->getName();
            $t = $p->getType();
            $decl['members'][] = [$name, $t ? $t->getName() : 'mixed'];
        }
    }

    $out[] = $decl;
}

echo json_encode($out, JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
