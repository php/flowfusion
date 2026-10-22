--TEST--
Translated seed (inferredbugs-csharp: i18n/5)
--FILE--
<?php
$dir = sys_get_temp_dir() . '/po_' . uniqid('', true);
@mkdir($dir . '/sv-SE', 0777, true);
file_put_contents($dir . '/sv-SE/messages.po', "msgid \"\"\nmsgstr \"\"\n\"Content-Type: text/plain; charset=utf-8\\n\"\n\n#. ctx\nmsgctxt \"ctx\"\nmsgid \"a\\n\"\n\"b\"\nmsgstr \"x\\t\"\n\"y\"\n\n#~ msgid \"old\"\n#~ msgstr \"gone\"\n");

$path = $dir . '/sv-SE/messages.po';
$lines = file($path, FILE_IGNORE_NEW_LINES);
$out = [];
for ($i = 0, $n = count($lines); $i < $n; $i++) {
    $line = $lines[$i];
    if ($line !== '' && $line[0] === '#') {
        continue;
    }
    if (strncmp($line, 'msgctxt', 7) === 0 || strncmp($line, 'msgid', 5) === 0 || strncmp($line, 'msgstr', 6) === 0 || ($line !== '' && $line[0] === '"')) {
        $out[] = $line;
    }
}
echo implode("\n", $out), "\n";
?>
--EXPECT--
