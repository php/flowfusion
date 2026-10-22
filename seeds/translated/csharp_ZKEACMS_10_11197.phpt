--TEST--
Translated seed (inferredbugs-csharp: ZKEACMS/10)
--FILE--
<?php
class WidgetBase {
    public $PageID;
    public $LayoutID;
    public function __construct($pageID = null, $layoutID = null) {
        $this->PageID = $pageID;
        $this->LayoutID = $layoutID;
    }
}

class PageService {
    public $changed = [];
    public function MarkChanged($pageID) {
        $this->changed[] = $pageID;
    }
}

class CacheManager {
    public $cleared = 0;
    public function Clear() {
        $this->cleared++;
    }
}

$pageService = new PageService();
$pageWidgetCacheManage = new CacheManager();
$pages = [];

$widgets = [
    new WidgetBase("page-1", null),
    new WidgetBase("page-1", "layout-1"),
    new WidgetBase("   ", "layout-2"),
    new WidgetBase("page-2", ""),
    new WidgetBase(null, "layout-3"),
    new WidgetBase("page-2", "layout-4"),
];

foreach ($widgets as $widget) {
    if (is_string($widget->PageID) && trim($widget->PageID) !== '' && !isset($pages[$widget->PageID])) {
        $pages[$widget->PageID] = true;
        $pageService->MarkChanged($widget->PageID);
    }
    if (is_string($widget->LayoutID) && trim($widget->LayoutID) !== '') {
        $pageWidgetCacheManage->Clear();
    }
}

echo count($pageService->changed) . "\n";
echo $pageWidgetCacheManage->cleared . "\n";
--EXPECT--
