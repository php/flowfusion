--TEST--
Translated seed (inferredbugs-csharp: ConvNetSharp/2)
--FILE--
<?php

class VolumeStorage {
    private array $hostBuffer;
    private int $totalLength;
    private bool $isOwner;

    public function __construct(array $array = [], int $length = -1) {
        if ($length !== -1) {
            $this->guessUnknownDimension($length);
        }

        $this->totalLength = count($array);
        $this->hostBuffer = array_fill(0, $this->totalLength, 0.0);
        $this->isOwner = true;

        if ($this->totalLength !== count($array)) {
            throw new InvalidArgumentException("Wrong dimensions");
        }

        $this->fillHostBuffer($array);
    }

    public function fillHostBuffer(array $array): void {
        for ($i = 0; $i < $this->totalLength; $i++) {
            $this->hostBuffer[$i] = $array[$i];
        }
    }

    public function clear(): void {
        for ($i = 0; $i < $this->totalLength; $i++) {
            $this->hostBuffer[$i] = 0.0;
        }
    }

    public function get(int $index): float {
        $this->copyToHost();
        return $this->hostBuffer[$index];
    }

    public function set(int $index, float $value): void {
        $this->copyToHost();
        $this->hostBuffer[$index] = $value;
    }

    public function copyToHost(): void {
        // Simulate copying to host
    }

    private function guessUnknownDimension(int $length): void {
        // Implement logic to guess dimension if needed
    }

    public function toArray(): array {
        $this->copyToHost();

        return $this->hostBuffer;
    }
}

function main() {
    $volume = new VolumeStorage([1.0, 2.0, 3.0, 4.0]);
    $volume->set(2, 5.0);
    $volume->clear();
    $array = $volume->toArray();

    foreach ($array as $value) {
        echo $value . "\n";
    }
}

main();
--EXPECT--
