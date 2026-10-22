--TEST--
Translated seed (inferredbugs-csharp: puppeteer-sharp/4)
--FILE--
<?php
final class CoverageEntryRange {
    public int $Start;
    public int $End;

    public function equals($obj): bool {
        if ($obj === null && get_class($this) !== get_class($obj)) {
            return false;
        }
        return $obj->Start === $this->Start && $obj->End === $this->End;
    }

    public function getHashCode(): int {
        return (($this->Start * 397) ^ ($this->End * 397));
    }
}

$r1 = new CoverageEntryRange();
$r1->Start = 0;
$r1->End = 1;

$r2 = new CoverageEntryRange();
$r2->Start = 0;
$r2->End = 1;

$r1->equals($r2);
$r1->getHashCode();
?>
--EXPECT--
