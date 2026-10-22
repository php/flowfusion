--TEST--
Translated seed (inferredbugs-csharp: resin/19)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_searcher_' . bin2hex(random_bytes(4));
mkdir($dir);
file_put_contents($dir . DIRECTORY_SEPARATOR . '0.ix', "ix");
file_put_contents($dir . DIRECTORY_SEPARATOR . '1.doc', "1\talpha\n2\tbeta\n");
file_put_contents($dir . DIRECTORY_SEPARATOR . '2.doc', "3\tgamma\n4\tdelta\n");

$readers = [];
$query = 'all';
$page = 0;
$size = 2;

$scored = [
    ['DocId' => 1, 'Score' => 1.5],
    ['DocId' => 2, 'Score' => 2.5],
    ['DocId' => 3, 'Score' => 3.5],
    ['DocId' => 4, 'Score' => 4.5],
];

$skip = $page * $size;
$paged = array_slice($scored, $skip, $size);
$docs = [];

foreach ($paged as $score) {
    $fileId = (string)intdiv($score['DocId'], 2) + 1;
    $fileName = $dir . DIRECTORY_SEPARATOR . $fileId . '.doc';
    if (!isset($readers[$fileId])) {
        $readers[$fileId] = new SplFileObject($fileName, 'r');
    }
    $reader = $readers[$fileId];
    $reader->rewind();
    $doc = null;
    while (!$reader->eof()) {
        $line = trim($reader->fgets());
        if ($line === '') continue;
        [$id, $text] = explode("\t", $line, 2);
        if ((int)$id === $score['DocId']) {
            $doc = ['Id' => (int)$id, 'Text' => $text, 'Fields' => []];
            break;
        }
    }
    if ($doc !== null) {
        $doc['Fields']['__score'] = sprintf('%.17g', $score['Score']);
        $docs[] = $doc;
    }
}

foreach ($readers as $r) {
    $r = null;
}

foreach ($docs as $d) {
    echo $d['Id'] . ':' . $d['Fields']['__score'] . "\n";
}
?>
--EXPECT--
