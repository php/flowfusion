--TEST--
Translated seed (inferredbugs-java: MercuryTrade/1)
--FILE--
<?php

$whisper = "User123";
$message = "to buy your item listed for 10 chaos in Breach";

$topPanel = [];
$tabName = substr($message, strpos($message, "(stash tab ") + 11, strpos($message, "; position:") - (strpos($message, "(stash tab ") + 11));
if ($tabName !== false) {
    $topPanel[] = "Tab: " . $tabName;
}
$topPanel[] = $whisper . ":";
$topPanel[] = "NEW";

$rectSize = [350, 140];
if (substr_count($message, "buttons") > 5) {
    $rectSize[1] = 160;
}

$itemName = substr($message, strpos($message, "to buy your ") + 12, strpos($message, " listed for") - (strpos($message, "to buy your ") + 12));
$price = substr($message, strpos($message, "listed for ") + 11, strpos($message, " in ") - (strpos($message, "listed for ") + 11));
$split = explode(" ", $price);
$currencyLabel = ($split[1] === "chaos" || $split[1] === "exalted") ? $split[1] : "";

$offer = substr($message, strrpos($message, "in Breach") + 10);
if ($tabName) {
    $offer = substr($message, strpos($message, ")") + 1);
}

echo implode(", ", $topPanel) . "\n";
echo "Item: " . $itemName . "\n";
echo "Price: " . $split[0] . " " . $currencyLabel . "\n";
echo "Offer: " . $offer . "\n";
--EXPECT--
