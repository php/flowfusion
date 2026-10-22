--TEST--
Translated seed (inferredbugs-java: bookkeeper/1)
--FILE--
<?php

function writePreV2Journal($journalDir, $numEntries) {
    $logId = time();
    $fn = $journalDir . "/" . dechex($logId) . ".txn";
    file_put_contents($fn, str_repeat("\0", 4 * 1024 * 1024));

    $data = "JournalTestData";
    $lastConfirmed = -1;
    for ($i = 1; $i <= $numEntries; $i++) {
        $packet = pack("N", strlen($data)) . $data;
        $lastConfirmed = $i;
        file_put_contents($fn, pack("N", strlen($packet)), FILE_APPEND);
        file_put_contents($fn, $packet, FILE_APPEND);
    }
}

function testTruncatedInEntryJournal() {
    $journalDir = sys_get_temp_dir() . "/bookie_journal";
    mkdir($journalDir);

    $ledgerDir = sys_get_temp_dir() . "/bookie_ledger";
    mkdir($ledgerDir);

    $journalChannel = writePreV2Journal($journalDir, 100);
    $zeros = str_repeat("\0", 2048);

    $fh = fopen($journalDir . "/1.txn", "r+");
    fseek($fh, filesize($journalDir . "/1.txn") - 768); // Simulating truncation
    fwrite($fh, $zeros);
    fclose($fh);

    writePreV2Journal($ledgerDir, 1);
    // Simulate Bookie behavior
    // Here should be the code to read entries, simulating the behaviour when corruption happens
}

testTruncatedInEntryJournal();
--EXPECT--
