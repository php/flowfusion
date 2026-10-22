--TEST--
Translated seed (inferredbugs-java: anserini/6)
--FILE--
<?php

class LookupNode {
    private $indexDir;
    private $index;

    public function __construct($indexDir) {
        if (!is_dir($indexDir) || !is_readable($indexDir)) {
            throw new InvalidArgumentException("$indexDir does not exist or is not a directory.");
        }
        $this->indexDir = $indexDir;
        $this->index = []; // Simulating an index
    }

    public function search($subject, $predicate = null) {
        echo "Querying started...\n";
        $matchingDocIds = array_filter(array_keys($this->index), function($id) use ($subject) {
            return $this->index[$id]['mid'] === $subject;
        });

        if (empty($matchingDocIds)) {
            echo "Cannot find subject: $subject\n";
        } else {
            foreach ($matchingDocIds as $docId) {
                foreach ($this->index[$docId]['fields'] as $field => $value) {
                    if ($predicate === null || $field === $predicate) {
                        echo "$field\t:\t $value\n";
                    }
                }
            }
        }
        echo "Querying completed.\n";
    }
}

$indexDir = $argv[1] ?? 'path/to/index'; // Example index directory
$subject = $argv[2] ?? 'freebase_mid_example'; // Example subject
$predicate = $argv[3] ?? null; // Optional predicate

$lookup = new LookupNode($indexDir);
$lookup->search($subject, $predicate);
--EXPECT--
