--TEST--
Translated seed (inferredbugs-java: Metanome/5)
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
    
    public function generateNewCsvFile() {
        return new SimpleRelationalInput($this->inputFile);
    }
}

class SimpleRelationalInput {
    private $data;
    private $position = 0;

    public function __construct($file) {
        $this->data = file($file, FILE_IGNORE_NEW_LINES);
    }

    public function next() {
        return $this->data[$this->position++];
    }
}

$csvFile = 'test.csv';
$expectedSeparator = ',';
$expectedQuotechar = '"';
$expectedEscape = '\\';
$expectedLine = 0;
$expectedStrictQuotes = true;
$expectedIgnoreLeadingWhiteSpace = true;

$generator = new CsvFileGenerator($csvFile, $expectedSeparator, $expectedQuotechar, $expectedEscape, $expectedLine, $expectedStrictQuotes, $expectedIgnoreLeadingWhiteSpace);
$csv = $generator->generateNewCsvFile();
$firstLine = $csv->next();
$secondLine = $csv->next();

$csv2 = $generator->generateNewCsvFile();
$firstLine2 = $csv2->next();
$secondLine2 = $csv2->next();
--EXPECT--
