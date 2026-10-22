--TEST--
Translated seed (inferredbugs-csharp: WebApi.Hal/5)
--FILE--
<?php
$resources = [
    ['id' => 1, 'name' => 'Org Name'],
    ['id' => 1, 'name' => 'Org Name', '_links' => ['self' => ['href' => '/orgs/1']]],
    ['id' => 1, 'name' => 'Org Name', '_links' => ['self' => ['title' => 'Org Name']]],
    ['id' => 1, 'name' => 'Org Name', '_links' => ['self' => null]],
];

foreach ($resources as $r) {
    $json = json_encode($r, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $xml = new SimpleXMLElement('<resource/>');
    foreach ($r as $k => $v) {
        if ($k === '_links') {
            $links = $xml->addChild('_links');
            foreach ($v as $rel => $link) {
                $node = $links->addChild($rel);
                if (is_array($link)) {
                    foreach ($link as $lk => $lv) {
                        $node->addChild($lk, htmlspecialchars((string)$lv, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
                    }
                } elseif ($link !== null) {
                    $node->addChild('href', htmlspecialchars((string)$link, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
                }
            }
        } else {
            $xml->addChild($k, htmlspecialchars((string)$v, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        }
    }
    $xmlOut = $xml->asXML();
    if ($json === false || $xmlOut === false) {
        exit(1);
    }
}
?>
--EXPECT--
