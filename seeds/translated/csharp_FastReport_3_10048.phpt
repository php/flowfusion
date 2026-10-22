--TEST--
Translated seed (inferredbugs-csharp: FastReport/3)
--FILE--
<?php

class PreparedPages
{
    private array $preparedPages = [];
    private int $curPage = 0;
    private string $addPageAction = 'WriteOver';

    public function addPage($page)
    {
        $this->curPage++;
        if ($this->curPage >= count($this->preparedPages) || $this->addPageAction !== 'WriteOver') {
            $this->preparedPages[] = $page;
            $this->addPageAction = 'WriteOver';
            $this->curPage = count($this->preparedPages) - 1;
        }
    }

    public function getPage($index)
    {
        if ($index >= 0 && $index < count($this->preparedPages)) {
            return $this->preparedPages[$index];
        }
        return null;
    }

    public function count()
    {
        return count($this->preparedPages);
    }

    public function clear()
    {
        $this->preparedPages = [];
        $this->curPage = 0;
    }

    public function runTest()
    {
        $this->addPage("Page 1");
        $this->addPage("Page 2");
        $this->addPage("Page 3");
        echo $this->getPage(0) . "\n"; // "Page 1"
        echo $this->getPage(1) . "\n"; // "Page 2"
        echo $this->getPage(2) . "\n"; // "Page 3"

        $this->curPage = 1;
        $this->addPage("Page 4"); // This should not add a new page because of WriteOver
        echo $this->count() . "\n"; // 3
    }
}

$preparedPages = new PreparedPages();
$preparedPages->runTest();
--EXPECT--
