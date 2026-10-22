--TEST--
Translated seed (inferredbugs-java: Metanome/6)
--FILE--
<?php

class CsvFileGenerator {
    public $inputFile;
    public $separator;
    public $quotechar;
    public $escape;
    public $line;
    public $strictQuotes;
    public $ignoreLeadingWhiteSpace;

    public function __construct($file, $separator, $quotechar, $escape, $line, $strictQuotes, $ignoreLeadingWhiteSpace) {
        $this->inputFile = $file;
        $this->separator = $separator;
        $this->quotechar = $quotechar;
        $this->escape = $escape;
        $this->line = $line;
        $this->strictQuotes = $strictQuotes;
        $this->ignoreLeadingWhiteSpace = $ignoreLeadingWhiteSpace;
    }

    public function generateNewCopy() {
        return new SimpleRelationalInput(["Line 1", "Line 2"]);
    }
}

class SimpleRelationalInput {
    private $lines;
    private $index;

    public function __construct($lines) {
        $this->lines = $lines;
        $this->index = 0;
    }

    public function next() {
        return $this->lines[$this->index++];
    }
}

$csvFile = 'test.csv';
$separator = ',';
$quotechar = '"';
$escape = '\\';
$line = 0;
$strictQuotes = true;
$ignoreLeadingWhiteSpace = true;

$generator = new CsvFileGenerator($csvFile, $separator, $quotechar, $escape, $line, $strictQuotes, $ignoreLeadingWhiteSpace);

$csv = $generator->generateNewCopy();
echo $csv->next() . "\n"; // Output: Line 1
echo $csv->next() . "\n"; // Output: Line 2

$csv2 = $generator->generateNewCopy();
echo $csv2->next() . "\n"; // Output: Line 1
echo $csv2->next() . "\n"; // Output: Line 2
--EXPECT--
