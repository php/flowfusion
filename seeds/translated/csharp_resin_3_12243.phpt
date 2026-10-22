--TEST--
Translated seed (inferredbugs-csharp: resin/3)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'searcher_bug_' . uniqid('', true);
@mkdir($dir);
file_put_contents($dir . DIRECTORY_SEPARATOR . '0.ix', 'x');

class IxInfo {
    public static function Load($path) { return new self; }
}
class QueryParser {
    public function Parse($query) { return $query === '' ? null : $query; }
}
class Collector {
    public function __construct($directory, $ix, &$postingContainers) {}
    public function Collect($q, $page, $size, $scorer) {
        $out = [];
        for ($i = 0; $i < 3; $i++) $out[] = (object)['DocId' => (string)$i];
        return $out;
    }
}
class DocContainer {
    public function __construct($directory, $containerId) {}
    public function Get($docId) { return (object)['Fields' => ['id' => $docId]]; }
    public function Dispose() {}
}
class Result { public $Docs; public $Total; }

$directory = $dir;
$parser = new QueryParser();
$scorer = null;
$docContainers = [];
$postingContainers = [];
$ix = IxInfo::Load($directory . DIRECTORY_SEPARATOR . '0.ix');

$query = 'x';
$page = 1;
$size = 2;
$collector = new Collector($directory, $ix, $postingContainers);
$q = $parser->Parse($query);
if ($q !== null) {
    $scored = $collector->Collect($q, $page, $size, $scorer);
    $skip = $page * $size;
    $paged = array_slice($scored, $skip, $size);
    $docs = [];
    foreach ($paged as $s) {
        $docId = $s->DocId;
        $containerId = preg_replace('/\d+$/', '', $docId);
        if (!isset($docContainers[$containerId])) $docContainers[$containerId] = new DocContainer($directory, $containerId);
        $docs[] = $docContainers[$containerId]->Get($docId)->Fields;
    }
    $result = new Result();
    $result->Docs = $docs;
    $result->Total = count($scored);
}
foreach ($docContainers as $dc) $dc->Dispose();
foreach ($postingContainers as $pc) if (is_object($pc) && method_exists($pc, 'Dispose')) $pc->Dispose();
?>
--EXPECT--
