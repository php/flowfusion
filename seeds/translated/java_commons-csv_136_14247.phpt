--TEST--
Translated seed (inferredbugs-java: commons-csv/136)
--FILE--
<?php

class CSVPrinter {
    private $handle;
    private $recordSeparator;

    public function __construct($handle, $format) {
        $this->handle = $handle;
        $this->recordSeparator = $format['recordSeparator'];
    }

    public function printRecord(...$values) {
        $line = [];
        foreach ($values as $value) {
            if (strpos($value, ',') !== false || strpos($value, '"') !== false) {
                $value = '"' . str_replace('"', '""', $value) . '"';
            }
            $line[] = $value;
        }
        fwrite($this->handle, implode(',', $line) . $this->recordSeparator);
    }

    public function close() {
        fclose($this->handle);
    }
}

$recordSeparator = "\n";

$format = ['recordSeparator' => $recordSeparator];
$sw = fopen('php://temp', 'r+');

$printer = new CSVPrinter($sw, $format);
$printer->printRecord("a", "b");
$printer->printRecord("a,b", "b");
$printer->printRecord("a", "b\nc");
$printer->printRecord("a", "b\r\nc");
$printer->printRecord("a", "b\\c");
$printer->printRecord("a", null, "b");
$printer->printRecord("a", "b,c", "d");
$printer->close();

rewind($sw);
echo stream_get_contents($sw);
fclose($sw);
--EXPECT--
