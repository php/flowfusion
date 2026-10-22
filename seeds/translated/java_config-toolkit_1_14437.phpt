--TEST--
Translated seed (inferredbugs-java: config-toolkit/1)
--FILE--
<?php

function loadLocalProperties($rootNode, $group) {
    if (empty($rootNode) || empty($group)) {
        die("rootNode or group cannot be empty.");
    }

    $properties = null;
    $localOverrideFile = findLocalOverrideFile();
    $xmlString = file_get_contents($localOverrideFile);
    $doc = new DOMDocument();
    $doc->loadXML($xmlString);
    $factoriesNode = $doc->documentElement;

    $factoryNode = findChild($factoriesNode, 'node-factory', 'root', $rootNode);
    if ($factoryNode) {
        $nodeGroup = findChild($factoryNode, 'group', 'id', $group);
        if ($nodeGroup) {
            $childNodes = $nodeGroup->childNodes;
            $properties = [];
            foreach ($childNodes as $item) {
                if ($item->hasAttributes()) {
                    $keyAttr = $item->attributes->getNamedItem('key');
                    if ($keyAttr) {
                        $propKey = $keyAttr->nodeValue;
                        $propVal = $item->nodeValue;
                        if ($propKey !== null && $propVal !== null) {
                            $properties[$propKey] = $propVal;
                        }
                    }
                }
            }
        }
    }
    return $properties;
}

function findLocalOverrideFile() {
    $evnKey = getenv('LOCAL_OVERRIDE_FILE_ENV_KEY');
    return $evnKey ?: 'default_local_override_file.xml';
}

function findChild($parent, $childNodeName, $attrName, $attrValue) {
    foreach ($parent->childNodes as $item) {
        if ($item->hasAttributes()) {
            $attr = $item->attributes->getNamedItem($attrName);
            if ($attr !== null && $attrValue === $attr->nodeValue) {
                return $item;
            }
        }
    }
    return null;
}

$properties = loadLocalProperties('exampleRoot', 'exampleGroup');
--EXPECT--
