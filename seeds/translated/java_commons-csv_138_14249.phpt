--TEST--
Translated seed (inferredbugs-java: commons-csv/138)
--FILE--
<?php

class CSVRecord {
    private $values;
    private $header;

    public function __construct(array $values, array $header = null) {
        $this->values = $values;
        $this->header = $header;
    }

    public function get($key) {
        if (is_int($key)) {
            return isset($this->values[$key]) ? $this->values[$key] : null;
        } elseif (is_string($key) && $this->header && isset($this->header[$key])) {
            return $this->values[$this->header[$key]];
        }
        throw new InvalidArgumentException("Invalid key");
    }

    public function isConsistent() {
        return $this->header ? count($this->values) == count($this->header) : true;
    }

    public function toMap() {
        return array_combine(array_keys($this->header), $this->values);
    }
}

$values = ["A", "B", "C"];
$header = ["first" => 0, "second" => 1, "third" => 2];
$record = new CSVRecord($values);
$recordWithHeader = new CSVRecord($values, $header);

// Accessing valid indices
$record->get(0);
$record->get(1);
$record->get(2);

// Accessing valid keys
$recordWithHeader->get("first");
$recordWithHeader->get("second");
$recordWithHeader->get("third");

// Triggering exceptions
try { $recordWithHeader->get("fourth"); } catch (Exception $e) {}
try { $record->get("first"); } catch (Exception $e) {}
try { $recordWithHeader->get(PHP_INT_MAX); } catch (Exception $e) {}
try { $recordWithHeader->get(-1); } catch (Exception $e) {}

$recordWithHeader->toMap();
--EXPECT--
