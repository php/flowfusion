--TEST--
Translated seed (inferredbugs-java: Metanome/7)
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

    public function __construct($inputFile, $separator, $quotechar, $escape, $line, $strictQuotes, $ignoreLeadingWhiteSpace) {
        $this->inputFile = $inputFile;
        $this->separator = $separator;
        $this->quotechar = $quotechar;
        $this->escape = $escape;
        $this->line = $line;
        $this->strictQuotes = $strictQuotes;
        $this->ignoreLeadingWhiteSpace = $ignoreLeadingWhiteSpace;
    }

    public function generateNewCopy() {
        return new CsvDataIterator($this->inputFile);
    }
}

class CsvDataIterator {
    private $file;
    private $lines;

    public function __construct($file) {
        $this->file = $file;
        $this->lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    public function next() {
        return array_shift($this->lines);
    }
}

$expectedFile = 'test.csv';
$expectedSeparator = ',';
$expectedQuotechar = '"';
$expectedEscape = '\\';
$expectedLine = 0;
$expectedStrictQuotes = true;
$expectedIgnoreLeadingWhiteSpace = true;

$generator = new CsvFileGenerator($expectedFile, $expectedSeparator, $expectedQuotechar, $expectedEscape, $expectedLine, $expectedStrictQuotes, $expectedIgnoreLeadingWhiteSpace);
$csv = $generator->generateNewCopy();

$firstLine = $csv->next();
$secondLine = $csv->next();

$csv2 = $generator->generateNewCopy();
$firstLine2 = $csv2->next();
$secondLine2 = $csv2->next();
--EXPECT--
