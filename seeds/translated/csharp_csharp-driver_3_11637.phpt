--TEST--
Translated seed (inferredbugs-csharp: csharp-driver/3)
--FILE--
<?php
abstract class TokenFactory {
    public static function getFactory(string $partitionerName) {
        if (str_ends_with($partitionerName, 'Murmur3Partitioner')) return M3PToken::$FACTORY;
        if (str_ends_with($partitionerName, 'OrderedPartitioner')) return OPPToken::$FACTORY;
        return null;
    }
    abstract public function fromString(string $tokenStr);
    abstract public function hash(string $partitionKey);
}

interface Token {}

final class M3PToken implements Token {
    public int $value;
    public static TokenFactory $FACTORY;
    public function __construct(int $value) { $this->value = $value; }
}
final class M3PTokenFactory extends TokenFactory {
    public function fromString(string $tokenStr) { return new M3PToken((int)$tokenStr); }
    public function hash(string $partitionKey) {
        $h = unpack('J', hash('xxh64', $partitionKey, true))[1];
        if ($h >= 0x8000000000000000) $h -= 0x10000000000000000;
        return new M3PToken($h === PHP_INT_MIN ? PHP_INT_MAX : $h);
    }
}

final class OPPToken implements Token {
    public string $value;
    public static TokenFactory $FACTORY;
    public function __construct(string $value) { $this->value = $value; }
}
final class OPPTokenFactory extends TokenFactory {
    public function fromString(string $tokenStr) { return new OPPToken($tokenStr); }
    public function hash(string $partitionKey) { return new OPPToken($partitionKey); }
}

M3PToken::$FACTORY = new M3PTokenFactory();
OPPToken::$FACTORY = new OPPTokenFactory();

$f1 = TokenFactory::getFactory('org.apache.cassandra.dht.Murmur3Partitioner');
$t1 = $f1->fromString((string)PHP_INT_MIN);
$t2 = $f1->hash("\0\1\2\3\4\5\6\7");
$f2 = TokenFactory::getFactory('OrderedPartitioner');
$t3 = $f2->fromString("abc");
$t4 = $f2->hash("abc");
echo get_class($t1), "\n", get_class($t2), "\n", get_class($t3), "\n", get_class($t4), "\n";
?>
--EXPECT--
