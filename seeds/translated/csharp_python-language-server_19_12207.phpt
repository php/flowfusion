--TEST--
Translated seed (inferredbugs-csharp: python-language-server/19)
--FILE--
<?php
class DocumentBuffer {
    public string $text = '';
    public int $version = 0;

    function reset(int $version, string $text): void {
        $this->version = $version;
        $this->text = $text;
    }

    function update(array $changes): void {
        usort($changes, function($a, $b) {
            return $b[0] <=> $a[0] ?: $b[1] <=> $a[1];
        });
        foreach ($changes as $c) {
            [$kind, $start, $end, $s] = $c;
            if ($kind === 'replaceall') {
                $this->text = $s;
                continue;
            }
            $len = strlen($this->text);
            $start = max(0, min($len, $start));
            $end = max(0, min($len, $end));
            if ($kind === 'insert') $this->text = substr($this->text, 0, $start) . $s . substr($this->text, $start);
            elseif ($kind === 'delete') $this->text = substr($this->text, 0, $start) . substr($this->text, $end);
            else $this->text = substr($this->text, 0, $start) . $s . substr($this->text, $end);
        }
        $this->version++;
    }
}

$doc = new DocumentBuffer();
$doc->reset(0, "def f(x):\n    return\n\ndef g(y):\n    return y * 2\n");
$doc->update([
    ['insert', 10, 10, ')'],
    ['insert', 10, 10, 'x'],
    ['insert', 10, 10, '('],
    ['insert', 10, 10, 'g'],
    ['insert', 10, 10, ' '],
]);
$doc->update([
    ['delete', 17, 18, ''],
    ['insert', 17, 17, 'x * 2'],
]);
$doc->update([
    ['replace', 21, 22, '300'],
]);
$doc->update([
    ['delete', 16, 25, ''],
    ['insert', 6, 6, '#'],
]);
echo $doc->text;
?>
--EXPECT--
