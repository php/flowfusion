--TEST--
Translated seed (inferredbugs-csharp: python-language-server/9)
--FILE--
<?php
$g = new class {
    public array $gp = [];
    public array $constraints = [];
};
$f = new class {
    public array $gp = [];
    public string $name = 'T';
};
$cls = new class {
    public array $gp = ['T' => 'int'];
    public array $mro = ['int', 'object'];
};
$staticReturn = $f;
$self = $cls;
if (isset($self->gp[$staticReturn->name])) {
    $r = $self->gp[$staticReturn->name];
} else {
    $r = null;
    foreach ($g->constraints as $c) {
        if (in_array($c, $self->mro, true)) {
            $r = $self;
            break;
        }
    }
    if ($r === null) {
        $r = $g->constraints[0] ?? null;
    }
}
echo is_object($r) ? get_class($r) : (string)$r;
?>
--EXPECT--
