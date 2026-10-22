--TEST--
Translated seed (inferredbugs-csharp: resin/31)
--FILE--
<?php
class Config {
    private array $data;
    function __construct(array $data) { $this->data = $data; }
    function get(string $key): string { return $this->data[$key] ?? ''; }
}

class LatinTokenizer {}
class TermQueryParser {}
class HttpQueryParser { function __construct($p, $t) {} }
class HttpBowQueryParser { function __construct($t) {} }
class SessionFactory { function __construct($dir, $tokenizer, $config) {} }

$services = [];
$config = new Config(['data_dir' => str_repeat('a', 0)]);
$tokenizer = new LatinTokenizer();

$services['SessionFactory'] = new SessionFactory($config->get('data_dir'), $tokenizer, $config);
$services['ITokenizer'] = $tokenizer;
$services['HttpQueryParser'] = new HttpQueryParser(new TermQueryParser(), $tokenizer);
$services['HttpBowQueryParser'] = new HttpBowQueryParser($tokenizer);

echo count($services), PHP_EOL;
--EXPECT--
