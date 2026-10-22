--TEST--
Translated seed (inferredbugs-java: Spout/4)
--FILE--
<?php

class SnapshotableShortArray {
    private $snapshot;
    private $live;
    private $dirtyArray;
    private $dirtyIndex = 0;

    public function __construct(array $initial, $dirtySize = 50) {
        $this->snapshot = $initial;
        $this->live = $initial;
        $this->dirtyArray = array_fill(0, $dirtySize, 0);
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
                $this->snapshot[$this->dirtyArray[$i]] = $this->live[$this->dirtyArray[$i]];
            }
        } else {
            $this->snapshot = $this->live;
        }
    }
}

$initial = [1, 2, 3, 4, 5];
$snapshotable = new SnapshotableShortArray($initial);
$snapshotable->set(0, 10);
$snapshotable->copySnapshot();
echo $snapshotable->get(0); // This will output 1, since we copied the snapshot before changing it.
--EXPECT--
