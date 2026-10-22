--TEST--
Translated seed (inferredbugs-csharp: dlr/3)
--FILE--
<?php
final class SourceLocation {
    public int $index;
    public int $line;
    public int $column;
    public bool $valid;
    public function __construct(int $index, int $line, int $column, bool $valid) {
        $this->index = $index;
        $this->line = $line;
        $this->column = $column;
        $this->valid = $valid;
    }
}
$none = new SourceLocation(0, 0, 0, true);
$invalid = new SourceLocation(0, 0, 0, false);
$cases = [
    [$none, $none],
    [$invalid, $invalid],
    [new SourceLocation(1, 2, 3, true), new SourceLocation(5, 6, 7, true)],
];
foreach ($cases as [$start, $end]) {
    if (($start->valid && $end->valid && $start->index > $end->index) || (($start->valid xor $end->valid))) {
        continue;
    }
    $length = $end->index - $start->index;
    $hash = $start->column ^ ($end->column << 7) ^ ($start->line << 14) ^ ($end->line << 23);
    $s = $start->line . ":" . $start->column . " - " . $end->line . ":" . $end->column;
    $d = $start->line . ":" . $start->column . "-" . $end->line . ":" . $end->column;
    $x = [$length, $hash, $s, $d];
}
echo "";
?>
--EXPECT--
