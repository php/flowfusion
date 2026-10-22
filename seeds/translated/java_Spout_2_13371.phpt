--TEST--
Translated seed (inferredbugs-java: Spout/2)
--FILE--
<?php

class SnapshotableShortArray {
    private $snapshot;
    private $live;
    private $dirtyArray;
    private $dirtyIndex = 0;

    public function __construct(array $initial, $dirtySize = 50) {
        $this->snapshot = array_fill(0, count($initial), 0);
        $this->live = array_fill(0, count($initial), 0);
        $this->dirtyArray = array_fill(0, $dirtySize, 0);
        foreach ($initial as $i => $value) {
            $this->snapshot[$i] = $value;
            $this->live[$i] = $value;
        }
    }

    public function get($index) {
        return $this->snapshot[$index];
    }

    public function getLive($index) {
        return $this->live[$index];
    }

    public function set($index, $value) {
        $this->live[$index] = $value;
        if ($this->dirtyIndex < count($this->dirtyArray)) {
            $this->dirtyArray[$this->dirtyIndex++] = $index;
        }
        return $this->snapshot[$index];
    }

    public function copySnapshot() {
        $length = $this->dirtyIndex;
        if ($length <= count($this->dirtyArray)) {
            for ($i = 0; $i < $length; $i++) {
                $index = $this->dirtyArray[$i];
                $this->snapshot[$index] = $this->live[$index];
            }
        } else {
            foreach ($this->live as $i => $value) {
                $this->snapshot[$i] = $value;
            }
        }
    }
}

$initial = range(-32768, 32767);
$snapshotableArray = new SnapshotableShortArray($initial);
$snapshotableArray->set(0, 100);
$snapshotableArray->copySnapshot();
--EXPECT--
