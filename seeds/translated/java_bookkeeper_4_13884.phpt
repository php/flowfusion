--TEST--
Translated seed (inferredbugs-java: bookkeeper/4)
--FILE--
<?php

$openFileLimit = 10;
$pageSize = 64;
$entriesPerPage = $pageSize / 8;
$pageLimit = 5;
$pageCount = 0;

$cleanLedgers = [];
$dirtyLedgers = [];
$pages = [];

function grabCleanPage($ledger, $entry) {
    global $pageCount, $pageLimit;
    if ($entry % 8 != 0) {
        throw new Exception("Entry not a multiple of 8");
    }
    if ($pageCount < $pageLimit) {
        $pageCount++;
        return ['ledger' => $ledger, 'firstEntry' => $entry, 'data' => str_repeat("\0", 64)];
    }
    return null;
}

function putEntryOffset($ledger, $entry, $offset) {
    global $pages, $entriesPerPage;
    $offsetInPage = $entry % $entriesPerPage;
    $pageEntry = $entry - $offsetInPage;
    if (!isset($pages[$ledger][$pageEntry])) {
        $page = grabCleanPage($ledger, $pageEntry);
        $pages[$ledger][$pageEntry] = $page;
    }
    $pages[$ledger][$pageEntry]['data'][$offsetInPage * 8] = pack("P", $offset);
}

function getEntryOffset($ledger, $entry) {
    global $pages, $entriesPerPage;
    $offsetInPage = $entry % $entriesPerPage;
    $pageEntry = $entry - $offsetInPage;
    if (isset($pages[$ledger][$pageEntry])) {
        return unpack("P", $pages[$ledger][$pageEntry]['data'][$offsetInPage * 8])[1];
    }
    return null;
}

$ledgerId = 1;
putEntryOffset($ledgerId, 0, 100);
putEntryOffset($ledgerId, 8, 200);
putEntryOffset($ledgerId, 16, 300);

echo getEntryOffset($ledgerId, 0) . "\n";
echo getEntryOffset($ledgerId, 8) . "\n";
echo getEntryOffset($ledgerId, 16) . "\n";
--EXPECT--
