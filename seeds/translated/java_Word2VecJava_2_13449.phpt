--TEST--
Translated seed (inferredbugs-java: Word2VecJava/2)
--FILE--
<?php

class Word2VecModel {
    private array $vocab;
    private int $layerSize;
    private array $vectors;

    public function __construct(array $vocab, int $layerSize, array $vectors) {
        $this->vocab = $vocab;
        $this->layerSize = $layerSize;
        $this->vectors = $vectors;
    }

    public function getVocab(): array {
        return $this->vocab;
    }

    public static function fromTextFile(string $file): Word2VecModel {
        $lines = file($file, FILE_IGNORE_NEW_LINES);
        $firstLine = explode(" ", array_shift($lines));
        $vocabSize = (int)$firstLine[0];
        $layerSize = (int)$firstLine[1];

        if ($vocabSize !== count($lines)) {
            throw new Exception("Vocabulary size mismatch.");
        }

        $vocab = [];
        $vectors = [];

        foreach ($lines as $line) {
            $values = explode(" ", $line);
            if (count($values) - 1 !== $layerSize) {
                throw new Exception("Layer size mismatch.");
            }
            $vocab[] = $values[0];
            for ($d = 1; $d < count($values); $d++) {
                $vectors[] = (float)$values[$d];
            }
        }

        return new Word2VecModel($vocab, $layerSize, $vectors);
    }
}

$model = Word2VecModel::fromTextFile('path/to/your/file.txt');
--EXPECT--
