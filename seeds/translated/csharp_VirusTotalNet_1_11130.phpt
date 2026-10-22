--TEST--
Translated seed (inferredbugs-csharp: VirusTotalNet/1)
--FILE--
<?php
$u = isset($argv[1]) ? trim($argv[1]) : "example.com";
if ($u === "") $u = "example.com";
if (!preg_match('~^https?://~i', $u)) $u = "http://" . $u;
$a = parse_url($u);
if ($a === false || !isset($a['host'])) $u = "http://example.com";
$base = rtrim($u, "/");
$urls = [];
for ($i = 0; $i < 30; $i++) $urls[] = $base . "/p" . $i . "?q=" . $i;
$joined = implode("\n", array_slice($urls, 0, 4));
$hashes = [];
for ($i = 0; $i < 5; $i++) $hashes[] = hash('sha256', "data" . $i);
$resource = implode(",", $hashes);
$links = [];
$links[] = (stripos($base, "https://") === 0 ? "https" : "http") . "://www.virustotal.com/file/" . $hashes[0] . "/analysis/";
$links[] = (stripos($base, "https://") === 0 ? "https" : "http") . "://www.virustotal.com/url/" . hash('sha256', filter_var($base, FILTER_SANITIZE_URL)) . "/analysis/";
echo $joined . "\n" . $resource . "\n" . implode("\n", $links) . "\n";
?>
--EXPECT--
