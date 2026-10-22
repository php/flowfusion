--TEST--
Translated seed (inferredbugs-csharp: Westwind.AspNetCore/1)
--FILE--
<?php
$markdown = "Hello **world**\n\n- [x] a\n- [ ] b\n\nhttps://example.com\n\n# Title\n";
$lines = preg_split('/\R/', $markdown);
$out = [];
$inList = false;
foreach ($lines as $line) {
    if ($line === '') {
        if ($inList) {
            $out[] = '</ul>';
            $inList = false;
        }
        continue;
    }
    if (preg_match('/^# (.+)$/', $line, $m)) {
        if ($inList) {
            $out[] = '</ul>';
            $inList = false;
        }
        $id = strtolower(preg_replace('/[^a-z0-9]+/', '-', $m[1]));
        $out[] = '<h1 id="' . trim($id, '-') . '">' . htmlspecialchars($m[1], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</h1>';
        continue;
    }
    if (preg_match('/^- \[(x| )\] (.+)$/i', $line, $m)) {
        if (!$inList) {
            $out[] = '<ul>';
            $inList = true;
        }
        $out[] = '<li><input type="checkbox"' . (strtolower($m[1]) === 'x' ? ' checked' : '') . ' disabled> ' . htmlspecialchars($m[2], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</li>';
        continue;
    }
    $line = preg_replace_callback('/\*\*(.+?)\*\*/', function ($m) {
        return '<strong>' . htmlspecialchars($m[1], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</strong>';
    }, $line);
    $line = preg_replace_callback('/https?:\/\/[^\s<]+/', function ($m) {
        $u = htmlspecialchars($m[0], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        return '<a href="' . $u . '">' . $u . '</a>';
    }, $line);
    $out[] = '<p>' . $line . '</p>';
}
if ($inList) $out[] = '</ul>';
echo implode("\n", $out), "\n";
--EXPECT--
