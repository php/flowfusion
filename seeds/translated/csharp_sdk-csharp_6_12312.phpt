--TEST--
Translated seed (inferredbugs-csharp: sdk-csharp/6)
--FILE--
<?php
class AttributedModel {
    public string $AttributedProperty;
}
class OtherModelClass {
    public string $Text;
}
$minimal = [
    'specversion' => '1.0',
    'id' => '1',
    'source' => '/',
    'type' => 't'
];
$obj = $minimal;
$obj['data'] = [ 'AttributedProperty' => 'test' ];
$decoded = json_decode(json_encode($obj), true);
$model = new AttributedModel();
$model->AttributedProperty = $decoded['data']['AttributedProperty'];
$obj = $minimal;
$decoded = json_decode(json_encode($obj), true);
$data = $decoded['data'] ?? null;
$obj = $minimal;
$obj['data_base64'] = base64_encode('{}');
$decoded = json_decode(json_encode($obj), true);
if (isset($decoded['data']) && isset($decoded['data_base64'])) {
    throw new Exception();
}
$cloudEvent = new stdClass();
$cloudEvent->Data = null;
$binary = json_decode('{"AttributedProperty":"test"}', true);
$model = new AttributedModel();
$model->AttributedProperty = $binary['AttributedProperty'];
$cloudEvent->Data = $model;
$cloudEvent = new stdClass();
$cloudEvent->Data = 'original';
$cloudEvent->Data = null;
$cloudEvent = new stdClass();
$cloudEvent->Data = new AttributedModel();
$cloudEvent->Data->AttributedProperty = 'test';
$encoded = json_encode(['AttributedProperty' => $cloudEvent->Data->AttributedProperty]);
$parsed = json_decode($encoded, true);
$cloudEvent = new stdClass();
$cloudEvent->Data = null;
$encoded = json_encode([]);
$parsed = json_decode($encoded, true);
$cloudEvent = new stdClass();
$cloudEvent->Data = new OtherModelClass();
$cloudEvent->Data->Text = 'Wrong type';
$cloudEvent = new stdClass();
$cloudEvent->Data = new AttributedModel();
$cloudEvent->Data->AttributedProperty = 'test';
$encoded = json_encode(['AttributedProperty' => $cloudEvent->Data->AttributedProperty]);
$parsed = json_decode($encoded, true);
$cloudEvent = new stdClass();
$cloudEvent->Data = null;
$encoded = '';
$cloudEvent = new stdClass();
$cloudEvent->Data = new OtherModelClass();
$cloudEvent->Data->Text = 'Wrong type';
echo "ok\n";
?>
--EXPECT--
