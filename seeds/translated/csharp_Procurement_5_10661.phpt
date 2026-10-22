--TEST--
Translated seed (inferredbugs-csharp: Procurement/5)
--FILE--
<?php
class Tab { public $Name; public $Hidden; public $srcL; public $srcC; public $srcR; function __construct($n,$h=false){$this->Name=$n;$this->Hidden=$h;$this->srcL="L:$n";$this->srcC="C:$n";$this->srcR="R:$n";} }
class Stash { public $Tabs=[]; public $NumberOfTabs=0; function __construct($tabs){$this->Tabs=$tabs;$this->NumberOfTabs=count($tabs);} function Add($s){foreach($s->Tabs as $t)$this->Tabs[]=$t;$this->NumberOfTabs=count($this->Tabs);} function ClearItems(){} }
class Item { public $Name; public $TypeLine; public $IconURL; function __construct($n,$t,$u){$this->Name=$n;$this->TypeLine=$t;$this->IconURL=$u;} }
$stash = new Stash([new Tab("tab0", true), new Tab("keep"), new Tab("skip", true), new Tab("keep2")]);
$myTabs = ["keep", "keep2"];
$onlyMyTabs = count($myTabs) != 0;
if ($stash->Tabs[0]->Hidden) $stash->ClearItems();
$skippedTabs = [];
if (!$onlyMyTabs) {
} else {
    $tabCount = 0;
    for ($i = 1; $i < $stash->NumberOfTabs; $i++) {
        if (in_array($stash->Tabs[$i]->Name, $myTabs, true)) {
            $stash->Add(new Stash([new Tab($stash->Tabs[$i]->Name)]));
            ++$tabCount;
        } else {
            $skippedTabs[] = $stash->Tabs[$i];
        }
    }
    foreach ($skippedTabs as $tab) {
        foreach ($stash->Tabs as $k => $v) {
            if ($v === $tab) unset($stash->Tabs[$k]);
        }
    }
    $stash->NumberOfTabs = $tabCount + 1;
}
$items = [new Item("", "TypeA", "u1"), new Item("NameB", "TypeB", "u2"), new Item("", "TypeA", "u1")];
$seen = [];
foreach ($items as $item) {
    $key = $item->IconURL . "|" . ($item->Name !== "" ? $item->Name : $item->TypeLine);
    if (isset($seen[$key])) continue;
    $seen[$key] = true;
    $name = $item->Name !== "" ? $item->Name : $item->TypeLine;
    $img = fopen("php://temp", "r+");
    fwrite($img, $name . ":" . $item->IconURL);
    rewind($img);
    stream_get_contents($img);
    fclose($img);
}
echo "ok\n";
?>
--EXPECT--
