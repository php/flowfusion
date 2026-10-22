--TEST--
Translated seed (inferredbugs-csharp: i18n/12)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'po_bug_' . uniqid();
@mkdir($dir, 0777, true);
@mkdir($dir . DIRECTORY_SEPARATOR . 'sv-SE', 0777, true);
file_put_contents($dir . DIRECTORY_SEPARATOR . 'sv-SE' . DIRECTORY_SEPARATOR . 'messages.po', "#. context\n#: ref1\nmsgctxt \"ctx\"\nmsgid \"a\\nb\"\nmsgstr \"x\\ty\\u0041\\141\\n\"\n\n#~ msgid \"old\"\n#~ msgstr \"z\"\n");

$path = $dir . DIRECTORY_SEPARATOR . 'sv-SE' . DIRECTORY_SEPARATOR . 'messages.po';
$lines = file($path, FILE_IGNORE_NEW_LINES);
$out = [];
for ($i = 0, $n = count($lines); $i < $n; $i++) {
    $line = $lines[$i];
    if ($line !== '' && $line[0] === '#' && strncmp($line, '#~', 2) !== 0) {
        continue;
    }
    if ($line === '' || strncmp($line, '#~', 2) === 0 || strncmp($line, 'msgid', 5) === 0 || strncmp($line, 'msgctxt', 7) === 0) {
        $buf = '';
        while ($i < $n) {
            $l = $lines[$i];
            if ($l !== '' && $l[0] === '#') {
                $i++;
                continue;
            }
            if (preg_match('/"((?:\\\\.|[^"])*)"/', $l, $m)) {
                $buf .= stripcslashes($m[1]);
                $i++;
                continue;
            }
            break;
        }
        $out[] = $buf;
        $i--;
    }
}
echo implode("\n", $out), "\n";
?>
--EXPECT--
