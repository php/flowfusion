--TEST--
Translated seed (inferredbugs-csharp: NetModular/3)
--FILE--
<?php
$paths = [null, '', __FILE__, sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_pdf_helper_' . uniqid('', true) . '.tmp'];
foreach ($paths as $p) {
    if ($p !== null && $p !== '' && !file_exists($p)) {
        file_put_contents($p, "%PDF-1.4\n%%EOF");
    }
    if ($p === null || $p === '' || !file_exists($p)) {
        continue;
    }
    $h = fopen($p, 'rb');
    if ($h !== false) {
        fread($h, 1);
        fclose($h);
    }
    $h = fopen($p, 'wb');
    if ($h !== false) {
        fwrite($h, "%PDF-1.4\n%%EOF");
        fclose($h);
    }
}
?>
--EXPECT--
