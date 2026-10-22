--TEST--
Translated seed (inferredbugs-csharp: azure-cosmos-dotnet-v3/1)
--FILE--
<?php
class MockCosmosUtil
{
    private static ?string $mockAccountKey = null;

    public static function MockAccountKey(): string
    {
        if (self::$mockAccountKey === null) {
            self::$mockAccountKey = '5BF89B4F04E44AC6B566484F8DBBFB37';
        }
        return self::$mockAccountKey;
    }
}

$accountKey = MockCosmosUtil::MockAccountKey();
$endpoint = 'http://localhost';
$uri1 = '/dbs/myDb/colls/myContainer';
$uri2 = '/dbs/myDb';
$range = ['min' => 'A', 'max' => 'B', 'minInclusive' => true, 'maxInclusive' => false];
$partitionKeyRangeId = 'range-1';
$resolvedRangeInfo = ['partitionKeyRange' => ['id' => $partitionKeyRangeId], 'continuationTokens' => []];

echo $endpoint . "\n";
echo $accountKey . "\n";
echo $uri1 . "\n";
echo $uri2 . "\n";
echo $range['min'] . $range['max'] . "\n";
echo $resolvedRangeInfo['partitionKeyRange']['id'] . "\n";
--EXPECT--
