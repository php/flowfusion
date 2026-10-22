--TEST--
Translated seed (inferredbugs-java: commons-compress/13)
--FILE--
<?php
$output = 'bla.tar';
$file1 = 'test1.xml';
$file1Size = filesize($file1);

$os = fopen($output, 'wb');
$entryName = 'testdata/test1.xml';
$modTime = 0;
$userId = 0;
$groupId = 0;
$userName = 'avalon';
$groupName = 'excalibur';
$mode = 0100000;

// Simulate writing to a tar archive
fwrite($os, "Creating entry: $entryName\n");
fwrite($os, pack("P", $modTime));
fwrite($os, pack("P", $file1Size));
fwrite($os, pack("P", $userId));
fwrite($os, pack("P", $groupId));
fwrite($os, "$userName\n$groupName\n");
fwrite($os, pack("P", $mode));

$inputFile = fopen($file1, 'rb');
stream_copy_to_stream($inputFile, $os);
fclose($inputFile);
fclose($os);

// Long name test
$longName = 'testdata/12345678901234567890123456789012345678901234567890123456789012345678901234567890123456.xml';
$file2 = 'test1.xml';
$file2Size = filesize($file2);
$os2 = fopen($output, 'wb');
$entryName2 = $longName;

// Simulate writing to a tar archive with a long name
fwrite($os2, "Creating entry: $entryName2\n");
fwrite($os2, pack("P", $modTime));
fwrite($os2, pack("P", $file2Size));
fwrite($os2, pack("P", $userId));
fwrite($os2, pack("P", $groupId));
fwrite($os2, "$userName\n$groupName\n");
fwrite($os2, pack("P", $mode));

$inputFile2 = fopen($file2, 'rb');
stream_copy_to_stream($inputFile2, $os2);
fclose($inputFile2);
fclose($os2);

// Testing entry with name too long
$toLongName = 'testdata/123456789012345678901234567890123456789012345678901234567890123456789012345678901234567.xml';
$os3 = fopen($output, 'wb');

try {
    fwrite($os3, "Creating entry: $toLongName\n");
    fwrite($os3, pack("P", $modTime));
    fwrite($os3, pack("P", $file2Size));
    fwrite($os3, pack("P", $userId));
    fwrite($os3, pack("P", $groupId));
    fwrite($os3, "$userName\n$groupName\n");
    fwrite($os3, pack("P", $mode));
    
    $inputFile3 = fopen($file2, 'rb');
    stream_copy_to_stream($inputFile3, $os3);
} catch (Exception $e) {
    // Handle exception silently
} finally {
    if (isset($inputFile3)) {
        fclose($inputFile3);
    }
    fclose($os3);
}
?>
--EXPECT--
