--TEST--
Translated seed (inferredbugs-csharp: OSS.Clients.SNS/1)
--FILE--
<?php
$xml = '<xml><MsgType>text</MsgType><FromUserName>a</FromUserName><ToUserName>b</ToUserName><Content>hi</Content></xml>';
$dom = new DOMDocument();
$dom->loadXML($xml);
$dirs = [];
foreach ($dom->documentElement->childNodes as $node) {
    if ($node->nodeType === XML_ELEMENT_NODE) $dirs[$node->nodeName] = $node->textContent;
}
$reply = ['ToUserName' => $dirs['FromUserName'], 'FromUserName' => $dirs['ToUserName'], 'CreateTime' => time(), 'MsgType' => 'text', 'Content' => $dirs['Content']];
$out = '<xml>';
foreach ($reply as $k => $v) $out .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>';
$out .= '</xml>';
echo $out;
?>
--EXPECT--
