--TEST--
Translated seed (inferredbugs-csharp: beutl/2)
--FILE--
<?php
$tabItems = [];
$selectedItem = null;

$layer = (object)['Name' => 'Layer1'];
$setter = (object)['Property' => (object)['Name' => 'Opacity']];
$vm = (object)['Setter' => $setter, 'Description' => 'desc'];
$propsVm = (object)['Layer' => $layer];

foreach ($tabItems as $item) {
    if (isset($item['DataContext']) && is_object($item['DataContext']) && ($item['DataContext']->Setter ?? null) === $setter) {
        $selectedItem = $item;
        break;
    }
}

if ($selectedItem === null && isset($propsVm->Layer) && isset($setter->Property)) {
    $item = [
        'Header' => $propsVm->Layer->Name . ' / ' . $setter->Property->Name,
        'DataContext' => (object)[
            'Layer' => $propsVm->Layer,
            'Setter' => $setter,
            'Description' => $vm->Description
        ],
        'Content' => (object)['Type' => 'AnimationTimeline'],
        'IsClosable' => true,
        'IsSelected' => false
    ];
    $tabItems[] = $item;
    $tabItems[array_key_last($tabItems)]['IsSelected'] = true;
}

echo "";
?>
--EXPECT--
