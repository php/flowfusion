--TEST--
Translated seed (inferredbugs-java: commons-compress/37)
--FILE--
<?php
$output = 'bla.ar';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

// Create archive
$archive = fopen($output, 'wb');
fwrite($archive, "AR\n");
fwrite($archive, file_get_contents($file1));
fwrite($archive, file_get_contents($file2));
fclose($archive);

// Read archive and remove file2
$input = fopen($output, 'rb');
$archiveData = fread($input, filesize($output));
fclose($input);

$remainingFiles = "AR\n" . substr($archiveData, strlen("AR\n") + filesize($file1));
$newOutput = 'bla2.ar';
$archive2 = fopen($newOutput, 'wb');
fwrite($archive2, $remainingFiles);
fclose($archive2);

// Verify size
$input2 = fopen($newOutput, 'rb');
$sum = strlen(fread($input2, filesize($newOutput)));
fclose($input2);
?>
--EXPECT--
