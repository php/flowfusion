--TEST--
Translated seed (inferredbugs-java: commons-imaging/3)
--FILE--
<?php

class PSDHeaderInfo {
    public $Version;
    public $Reserved;
    public $Channels;
    public $Rows;
    public $Columns;
    public $Depth;
    public $Mode;

    public function __construct(int $Version, array $Reserved, int $Channels, int $Rows, int $Columns, int $Depth, int $Mode) {
        $this->Version = $Version;
        $this->Reserved = $Reserved;
        $this->Channels = $Channels;
        $this->Rows = $Rows;
        $this->Columns = $Columns;
        $this->Depth = $Depth;
        $this->Mode = $Mode;
    }

    public function dump() {
        echo "\nHeader\n";
        echo "Version: " . $this->Version . " (" . dechex($this->Version) . ")\n";
        echo "Channels: " . $this->Channels . " (" . dechex($this->Channels) . ")\n";
        echo "Rows: " . $this->Rows . " (" . dechex($this->Rows) . ")\n";
        echo "Columns: " . $this->Columns . " (" . dechex($this->Columns) . ")\n";
        echo "Depth: " . $this->Depth . " (" . dechex($this->Depth) . ")\n";
        echo "Mode: " . $this->Mode . " (" . dechex($this->Mode) . ")\n";
        echo "Reserved: " . count($this->Reserved) . "\n";
    }
}

$header = new PSDHeaderInfo(1, array_fill(0, 5, 0), 3, 400, 300, 8, 0);
$header->dump();
--EXPECT--
