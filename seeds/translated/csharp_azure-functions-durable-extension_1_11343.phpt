--TEST--
Translated seed (inferredbugs-csharp: azure-functions-durable-extension/1)
--FILE--
<?php
$availableFunctions = [];
$calledFunctions = [];

$code = <<<'PHP'
<?php
class F {
    #[Attribute]
    public function a(string $x): int { return 1; }
    public function b() {
        $this->CallActivityAsync("foo", 1);
        $this->CallActivityWithRetryAsync("bar", 2, 3);
        $this->CallActivityAsync($this->name ?? null);
    }
}
PHP;

if (function_exists('token_get_all')) {
    $tokens = token_get_all($code);
    $n = count($tokens);
    for ($i = 0; $i < $n; $i++) {
        $t = $tokens[$i];
        if (is_array($t) && $t[0] === T_STRING) {
            $s = $t[1];
            if (strncmp($s, 'CallActivityAsync', 17) === 0 || strncmp($s, 'CallActivityWithRetryAsync', 26) === 0) {
                $calledFunctions[] = $s;
            }
        }
        if (is_array($t) && $t[0] === T_ATTRIBUTE) {
            $availableFunctions[] = '#[';
        }
    }
}

echo count($availableFunctions) . ':' . count($calledFunctions) . PHP_EOL;
--EXPECT--
