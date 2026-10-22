--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/10)
--FILE--
<?php

class LongModificationFactory {
    const MODIFICATION_COUNT = 5;
    const MAX_MODIFICATION_VALUE = 32000;
    static $modificationsFromFile;

    static function add($summand) {
        return $summand + 1;
    }

    static function sub($subtrahend) {
        return $subtrahend - 1;
    }

    static function xor($value) {
        return $value ^ 1;
    }

    static function explicitValue($value) {
        return $value;
    }

    static function modificationsFromFile() {
        if (self::$modificationsFromFile === null) {
            self::$modificationsFromFile = [];
            $lines = file('modifications.txt', FILE_IGNORE_NEW_LINES);
            foreach ($lines as $line) {
                $value = trim(explode(" ", $line)[0]);
                self::$modificationsFromFile[] = self::explicitValue((int)$value);
            }
        }
        return self::$modificationsFromFile;
    }

    static function createRandomModification() {
        $r = rand(0, self::MODIFICATION_COUNT - 1);
        $modification = rand(0, self::MAX_MODIFICATION_VALUE);
        switch ($r) {
            case 0:
                return self::add($modification);
            case 1:
                return self::sub($modification);
            case 2:
                return self::xor($modification);
            case 3:
                return self::explicitValue($modification);
            case 4:
                $modifications = self::modificationsFromFile();
                return $modifications[rand(0, count($modifications) - 1)];
        }
        return null;
    }
}

echo LongModificationFactory::createRandomModification();
--EXPECT--
