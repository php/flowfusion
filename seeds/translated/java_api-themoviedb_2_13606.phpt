--TEST--
Translated seed (inferredbugs-java: api-themoviedb/2)
--FILE--
<?php

class TmdbChanges {
    public function getChangeList($type) {
        return $this->mockApiResponse($type);
    }

    private function mockApiResponse($type) {
        if ($type === 'movie') {
            return ['results' => ['change1', 'change2']];
        }
        return ['results' => []];
    }
}

$instance = new TmdbChanges();
$result = $instance->getChangeList('movie');
if (empty($result['results'])) {
    echo "No movie changes.";
} else {
    echo "Movie changes found.";
}
--EXPECT--
