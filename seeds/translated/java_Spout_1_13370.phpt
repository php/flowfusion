--TEST--
Translated seed (inferredbugs-java: Spout/1)
--FILE--
<?php

class SnapshotableShortArray {
    private array $snapshot;
    private array $live;
    private array $dirtyArray;
    private int $dirtyIndex = 0;

    public function __construct(array $initial, int $dirtySize = 50) {
        $this->snapshot = $initial;
        $this->live = $initial;
        $this->dirtyArray = array_fill(0, $dirtySize, 0);
    }

    public function get(int $index): int {
        return $this->snapshot[$index];
    }

    public function getLive(int $index): int {
        return $this->live[$index];
    }

    public function set(int $index, int $value): int {
        $this->live[$index] = $value;
        if ($this->dirtyIndex < count($this->dirtyArray)) {
            $this->dirtyArray[$this->dirtyIndex++] = $index;
        }
        return $this->snapshot[$index];
    }

    public function copySnapshot(): void {
        $length = $this->dirtyIndex;
        if ($length <= count($this->dirtyArray)) {
            for ($i = 0; $i < $length; $i++) {
                $index = $this->dirtyArray[$i];
                $this->snapshot[$index] = $this->live[$index];
            }
        } else {
            $this->snapshot = $this->live;
        }
    }
}

$initial = [1, 2, 3, 4, 5];
$snapshotable = new SnapshotableShortArray($initial);
$snapshotable->set(2, 10);
$snapshotable->copySnapshot();
--EXPECT--
