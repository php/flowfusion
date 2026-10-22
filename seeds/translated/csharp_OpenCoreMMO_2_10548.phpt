--TEST--
Translated seed (inferredbugs-csharp: OpenCoreMMO/2)
--FILE--
<?php
class Item {
    public int $clientId;
    public ?int $amount;
    public function __construct(int $clientId, ?int $amount = null) { $this->clientId = $clientId; $this->amount = $amount; }
}
class Tile {
    public array $topItems = [];
    public array $downItems = [];
    public function __construct(array $topItems = [], array $downItems = []) { $this->topItems = $topItems; $this->downItems = $downItems; }
    public function addItem(Item $item): void {
        if ($item->amount !== null && !empty($this->downItems) && $this->downItems[0]->clientId === $item->clientId && $this->downItems[0]->amount !== null) {
            $sum = $this->downItems[0]->amount + $item->amount;
            if ($sum <= 100) $this->downItems[0]->amount = $sum;
            else { $this->downItems[0]->amount = 100; array_splice($this->downItems, 1, 0, [new Item($item->clientId, $sum - 100)]); }
            return;
        }
        $this->downItems[] = $item;
    }
    public function removeItem(Item $item, int $amount): void {
        foreach ($this->downItems as $i => $it) {
            if ($it === $item) {
                if ($it->amount !== null && $amount < $it->amount) $it->amount -= $amount;
                else array_splice($this->downItems, $i, 1);
                return;
            }
        }
    }
}
$tile = new Tile([new Item(1, 1)], [new Item(100, 100), new Item(200), new Item(500, 60)]);
$tile->addItem(new Item(500, 100));
$tile->removeItem($tile->downItems[2], 40);
$dest = new Tile();
$dest->addItem(new Item(500, 3));
$dest->addItem(new Item(500, 100));
echo count($tile->downItems) . "\n";
echo $tile->downItems[0]->clientId . ":" . $tile->downItems[0]->amount . "\n";
echo $dest->downItems[0]->clientId . ":" . $dest->downItems[0]->amount . "\n";
?>
--EXPECT--
