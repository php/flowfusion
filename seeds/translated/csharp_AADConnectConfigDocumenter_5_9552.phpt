--TEST--
Translated seed (inferredbugs-csharp: AADConnectConfigDocumenter/5)
--FILE--
<?php

class ConnectorDocumenter {
    private $connectorName;
    private $connectorGuid;
    private $connectorCategory;
    private $connectorSubType;

    public function __construct($connectorName, $configXml) {
        $this->connectorName = $connectorName;
        $this->initialize($configXml);
    }

    private function initialize($configXml) {
        $connector = $this->xpathSelectElement($configXml, "//ma-data[name ='" . $this->connectorName . "']");

        if ($connector) {
            $this->connectorGuid = (string) $this->xpathSelectElement($connector, "id");
            $this->connectorCategory = (string) $this->xpathSelectElement($connector, "category");
            $this->connectorSubType = (string) $this->xpathSelectElement($connector, "subtype");
        }
    }

    private function xpathSelectElement($xml, $xpath) {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpathObj = new DOMXPath($dom);
        return $xpathObj->query($xpath)->item(0);
    }

    public function getConnectorInfo() {
        return [
            'name' => $this->connectorName,
            'guid' => $this->connectorGuid,
            'category' => $this->connectorCategory,
            'subtype' => $this->connectorSubType,
        ];
    }
}

$xmlConfig = <<<XML
<ma-data>
    <name>SampleConnector</name>
    <id>12345</id>
    <category>TestCategory</category>
    <subtype>TestSubtype</subtype>
</ma-data>
XML;

$connectorDocumenter = new ConnectorDocumenter("SampleConnector", $xmlConfig);
$info = $connectorDocumenter->getConnectorInfo();

foreach ($info as $key => $value) {
    echo ucfirst($key) . ": " . $value . "\n";
}
--EXPECT--
