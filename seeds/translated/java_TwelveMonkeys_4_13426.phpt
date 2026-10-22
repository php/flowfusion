--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/4)
--FILE--
<?php

class TIFFImageMetadata {
    const RATIONAL_SCALE_FACTOR = 100000;

    private $original;
    private $ifd;

    public function __construct($ifd = null) {
        $this->ifd = $ifd ?: [];
        $this->original = $this->ifd;
    }

    public function getNativeTree() {
        $root = new SimpleXMLElement('<root/>');
        $this->asTree($this->ifd, $root);
        return $root;
    }

    private function asTree($ifd, $node) {
        foreach ($ifd as $entry) {
            $tagNode = $node->addChild('TIFFField');
            $tagNode->addAttribute('number', $entry['identifier']);

            if ($entry['fieldName']) {
                $tagNode->addAttribute('name', $entry['fieldName']);
            }

            $count = count($entry['value']);
            $valueNode = $tagNode->addChild('TIFFValue');

            if ($entry['type'] === 'undefined') {
                $valueNode->addAttribute('value', implode(',', $entry['value']));
            } else {
                foreach ($entry['value'] as $value) {
                    $elementNode = $valueNode->addChild($entry['type']);
                    $elementNode->addAttribute('value', $value);
                }
            }
        }
    }

    public function setFromTree($root) {
        $this->ifd = [];
        foreach ($root->children() as $field) {
            $entry = [
                'identifier' => (int) $field['number'],
                'fieldName' => (string) $field['name'],
                'value' => []
            ];
            foreach ($field->children() as $value) {
                $entry['value'][] = (string) $value['value'];
            }
            $this->ifd[$entry['identifier']] = $entry;
        }
    }
}

$metadata = new TIFFImageMetadata();
$metadata->setFromTree(simplexml_load_string('<root><TIFFField number="1" name="SampleField"><TIFFValue><Value value="100"/></TIFFValue></TIFFField></root>'));
$tree = $metadata->getNativeTree();
echo $tree->asXML();
--EXPECT--
