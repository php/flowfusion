--TEST--
Translated seed (inferredbugs-csharp: i18n/10)
--FILE--
<?php
$dir = sys_get_temp_dir() . '/po_bug_' . uniqid();
@mkdir($dir, 0777, true);
@mkdir("$dir/en", 0777, true);
file_put_contents("$dir/en/messages.po", "msgid \"\"\nmsgstr \"\"\n\"Content-Type: text/plain; charset=utf-8\\n\"\n\n# comment\n#. extracted\n#: ref1\nmsgctxt \"ctx\"\nmsgid \"a\\n\"\n\"b\"\nmsgstr \"x\\u0041\\141\\n\"\n\nmsgid \"plain\"\nmsgstr \"ok\"\n");

$path = "$dir/en/messages.po";
$out = [];
if (is_file($path)) {
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    $i = 0;
    while ($i < count($lines)) {
        $line = $lines[$i++];
        if ($line === '' || $line[0] !== '#') continue;
        $ctx = null; $id = ''; $str = '';
        while ($i < count($lines) && isset($lines[$i][0]) && $lines[$i][0] === '#') $i++;
        if ($i < count($lines) && str_starts_with($lines[$i], 'msgctxt')) {
            preg_match('/"([^"]*)"/', $lines[$i++], $m);
            $ctx = $m[1] ?? null;
        }
        if ($i < count($lines) && str_starts_with($lines[$i], 'msgid')) {
            preg_match('/"([^"]*)"/', $lines[$i++], $m);
            $id .= $m[1] ?? '';
            while ($i < count($lines) && isset($lines[$i][0]) && $lines[$i][0] === '"') {
                preg_match('/"([^"]*)"/', $lines[$i++], $m);
                $id .= $m[1] ?? '';
            }
        }
        if ($i < count($lines) && str_starts_with($lines[$i], 'msgstr')) {
            preg_match('/"([^"]*)"/', $lines[$i++], $m);
            $str .= $m[1] ?? '';
            while ($i < count($lines) && isset($lines[$i][0]) && $lines[$i][0] === '"') {
                preg_match('/"([^"]*)"/', $lines[$i++], $m);
                $str .= $m[1] ?? '';
            }
        }
        $id = stripcslashes($id);
        $str = stripcslashes($str);
        $out[] = [$ctx, $id, $str];
    }
}
echo count($out) . "\n";
?>
--EXPECT--
