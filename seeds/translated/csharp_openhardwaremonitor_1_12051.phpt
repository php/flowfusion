--TEST--
Translated seed (inferredbugs-csharp: openhardwaremonitor/1)
--FILE--
<?php
$report = '';
$driver = null;
$fileName = null;
$isaBusMutex = fopen(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ring0_mutex_' . md5(__FILE__), 'c');
if ($isaBusMutex) flock($isaBusMutex, LOCK_EX | LOCK_NB);

$bus = 255;
$device = 31;
$function = 7;
$pciAddress = (($bus & 0xFF) << 8) | (($device & 0x1F) << 3) | ($function & 7);
$regAddress = 4;
if (($regAddress & 3) !== 0) $regAddress = 0;

$msrIndex = 0x1B;
$eax = $msrIndex & 0xFFFFFFFF;
$edx = ($msrIndex >> 32) & 0xFFFFFFFF;
$combined = (($edx & 0xFFFFFFFF) << 32) | ($eax & 0xFFFFFFFF);

$ioPort = 0x3F8;
$ioValue = $ioPort & 0xFF;

$memory = str_repeat("\0", 16);
$memory[0] = chr(($pciAddress ^ $combined) & 0xFF);
$memory[15] = chr($ioValue);

if ($isaBusMutex) {
  flock($isaBusMutex, LOCK_UN);
  fclose($isaBusMutex);
}
?>
--EXPECT--
