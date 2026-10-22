--TEST--
Translated seed (inferredbugs-java: api-themoviedb/1)
--FILE--
<?php

class TmdbChanges {
    public function getChangeList($type) {
        return $this->mockApiResponse($type);
    }

    private function mockApiResponse($type) {
        if ($type === 'MOVIE') {
            return ['results' => ['change1', 'change2']];
        }
        return ['results' => []];
    }
}

$instance = new TmdbChanges();
$result = $instance->getChangeList('MOVIE');
if (empty($result['results'])) {
    exit("No movie changes.");
}
--EXPECT--
