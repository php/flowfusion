--TEST--
Translated seed (inferredbugs-csharp: sdk-csharp/9)
--FILE--
<?php
class AttributedModel {
    public const JsonPropertyName = 'attributedProperty';
    public string $AttributedProperty;
}
class OtherModelClass {
    public string $Text;
}
$event = ['data' => [AttributedModel::JsonPropertyName => 'test']];
$model = json_decode(json_encode($event), true)['data'];
$event['data'] = $model;
$decoded = (object)['Data' => (object)$event['data']];
$event2 = ['data_base64' => base64_encode('{}')];
json_decode(json_encode($event2), true);
$binary = json_decode('{"'.AttributedModel::JsonPropertyName.'":"test"}', true);
$cloudEvent = (object)['Data' => null];
$cloudEvent->Data = (object)$binary;
$cloudEvent->Data = null;
$structured = ['data' => [AttributedModel::JsonPropertyName => 'test']];
json_encode($structured);
$noData = [];
json_encode($noData);
$wrong = new OtherModelClass();
$wrong->Text = 'Wrong type';
$binaryData = json_encode([AttributedModel::JsonPropertyName => 'test']);
json_decode($binaryData, true);
$empty = '';
json_decode($empty, true);
echo "";
?>
--EXPECT--
