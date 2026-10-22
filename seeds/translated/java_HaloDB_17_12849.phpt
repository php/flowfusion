--TEST--
Translated seed (inferredbugs-java: HaloDB/17)
--FILE--
<?php

class HaloDB {
    private $currentWriteFile = null;
    private $staleDataPerFileMap = [];
    private $nextFileId = 0;
    private $isClosing = false;
    private $options;

    public function __construct($options) {
        $this->options = $options;
    }

    public static function open($directory, $options) {
        $db = new HaloDB($options);
        $db->nextFileId = time(); // simulate next file id
        return $db;
    }

    public function put($key, $value) {
        if (strlen($key) > 127) {
            throw new Exception("key length cannot exceed 127");
        }
        $this->rollOverCurrentWriteFile([$key, $value]);
        // Simulate writing the record
        return true;
    }

    private function rollOverCurrentWriteFile($record) {
        $size = strlen($record[0]) + strlen($record[1]);
        if ($this->currentWriteFile === null || strlen($this->currentWriteFile) + $size > $this->options['maxFileSize']) {
            $this->currentWriteFile = '';
        }
    }

    public function delete($key) {
        if ($this->isClosing) return;
        $this->staleDataPerFileMap[$key] = true; // mark as stale
    }

    public function size() {
        return count($this->staleDataPerFileMap);
    }

    public function close() {
        $this->isClosing = true;
        $this->currentWriteFile = null;
        // Simulate closing the database
    }
}

$options = ['maxFileSize' => 1024];
$db = HaloDB::open('/path/to/db', $options);
$db->put('testKey', 'testValue');
$db->delete('testKey');
echo $db->size();
$db->close();
--EXPECT--
