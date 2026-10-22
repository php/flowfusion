--TEST--
Translated seed (inferredbugs-csharp: ZKEACMS/4)
--FILE--
<?php
class WidgetBase {
    public $PageID = '';
    public $LayoutID = '';
}
class ZoneService {
    function getZonesByPageId($id) { return [['ID' => 1, 'ZoneName' => 'Page-' . $id]]; }
    function getZonesByLayoutId($id) { return [['ID' => 2, 'ZoneName' => 'Layout-' . $id]]; }
}
class ViewDataZonesAttribute {
    public $viewData = [];
    function onActionExecuted($result, $controller, $zoneService) {
        if (is_array($result) && isset($result['Model']) && $result['Model'] instanceof WidgetBase) {
            $widget = $result['Model'];
            if ($widget->PageID !== '' && $widget->PageID !== null) {
                $controller->viewData['Zones'] = array_map(fn($z) => ['ID' => $z['ID'], 'ZoneName' => $z['ZoneName']], $zoneService->getZonesByPageId($widget->PageID));
            } elseif ($widget->LayoutID !== '' && $widget->LayoutID !== null) {
                $controller->viewData['Zones'] = array_map(fn($z) => ['ID' => $z['ID'], 'ZoneName' => $z['ZoneName']], $zoneService->getZonesByLayoutId($widget->LayoutID));
            }
        }
    }
    function onActionExecuting($ctx) {}
}
$controller = (object)['viewData' => []];
$attr = new ViewDataZonesAttribute();
$service = new ZoneService();
$w = new WidgetBase();
$w->PageID = '123';
$attr->onActionExecuted(['Model' => $w], $controller, $service);
$w2 = new WidgetBase();
$w2->LayoutID = 'abc';
$attr->onActionExecuted(['Model' => $w2], $controller, $service);
echo "ok\n";
?>
--EXPECT--
