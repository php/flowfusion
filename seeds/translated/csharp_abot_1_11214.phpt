--TEST--
Translated seed (inferredbugs-csharp: abot/1)
--FILE--
<?php
$base = 'http://a.com/';
$html = '<html><head><link rel="canonical" href="http://a.com/page1" /></head><body><a href="http://a.com/page2"></a><a href="/aaa/a.html" rel="nofollow"></a><a href="/bbb/b.html" rel="nofollow" /></a><a href="http://a.com/search?rls=en&amp;q=stack+overflow"></a><base href="http://bbb.com"><a href="/ccc/c.html"></a></body></html>';
preg_match_all('/<(a|area|link)\b[^>]*\bhref\s*=\s*(["\'])(.*?)\2[^>]*>(.*?)<\/\1>|<(a|area|link)\b[^>]*\bhref\s*=\s*(["\'])(.*?)\6[^>]*\/?>/is', $html, $m, PREG_SET_ORDER);
$links = [];
foreach ($m as $x) {
    $tag = strtolower($x[1] ?: $x[5]);
    $href = html_entity_decode($x[3] ?: $x[7], ENT_QUOTES | ENT_HTML5);
    if (preg_match('/^(javascript|mailto|tel|callto|ftp|file):/i', $href) || preg_match('/^https?:\/\/[^\/]+$/i', $href)) {
        $u = $href;
    } elseif (str_starts_with($href, '/')) {
        $u = 'http://bbb.com' . $href;
    } else {
        $u = $href;
    }
    if ($tag === 'link' || !preg_match('/\bnofollow\b/i', $x[0])) $links[$u] = true;
}
foreach (array_keys($links) as $u) echo $u, "\n";
?>
--EXPECT--
