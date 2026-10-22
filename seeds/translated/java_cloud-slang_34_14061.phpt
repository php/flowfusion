--TEST--
Translated seed (inferredbugs-java: cloud-slang/34)
--FILE--
<?php

class BranchAggregatorListener {

    public function aggregate() {
        $branchesDataByPath = [];

        $events = $this->getEvents();
        $groups = $this->groupByPath($events);

        foreach ($groups as $path => $subGroup) {
            $branchesData = $this->buildBranchesData($subGroup);
            $branchesDataByPath[$path] = $branchesData;
        }

        return $branchesDataByPath;
    }

    private function groupByPath($events) {
        $groups = [];
        foreach ($events as $event) {
            $path = $event['path'];
            if (!isset($groups[$path])) {
                $groups[$path] = [];
            }
            $groups[$path][] = $event;
        }
        return $groups;
    }

    private function buildBranchesData($data) {
        $branches = [];
        foreach ($data as $branchData) {
            $path = $branchData['path'];
            $stepName = $branchData['stepName'];
            $returnValues = $branchData['returnValues'];
            $branches[] = [
                'path' => $path,
                'stepName' => $stepName,
                'outputs' => $returnValues['outputs'],
                'result' => $returnValues['result']
            ];
        }
        return $branches;
    }

    private function getEvents() {
        return [
            ['path' => 'path1', 'stepName' => 'step1', 'returnValues' => ['outputs' => [], 'result' => 'result1']],
            ['path' => 'path2', 'stepName' => 'step2', 'returnValues' => ['outputs' => [], 'result' => 'result2']],
            ['path' => 'path1', 'stepName' => 'step3', 'returnValues' => ['outputs' => [], 'result' => 'result3']]
        ];
    }

}

$listener = new BranchAggregatorListener();
$listener->aggregate();
--EXPECT--
