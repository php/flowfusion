--TEST--
Translated seed (inferredbugs-java: commons-csv/137)
--FILE--
<?php

class CSVPrinter {
    private $output;
    private $recordSeparator = "\n";

    public function __construct() {
        $this->output = '';
    }

    public function printRecord(...$values) {
        $line = [];
        foreach ($values as $value) {
            if ($value === null) {
                $line[] = '';
            } else {
                $line[] = str_contains($value, ',') ? '"' . str_replace('"', '""', $value) . '"' : $value;
            }
        }
        $this->output .= implode(',', $line) . $this->recordSeparator;
    }

    public function toString() {
        return $this->output;
    }
}

function randStr() {
    $chars = ["\r", "\n", "\t", "\f", " ", ",", '"', "'", "\\", ...array_map('chr', range(0, 255))];
    return implode('', array_map(fn() => $chars[array_rand($chars)], range(1, rand(1, 20))));
}

$printer = new CSVPrinter();
for ($i = 0; $i < rand(1, 4); $i++) {
    $printer->printRecord(randStr(), randStr());
}
echo $printer->toString();
--EXPECT--
