--TEST--
Translated seed (inferredbugs-java: ark-tweet-nlp/3)
--FILE--
<?php

class RunTagger {
    public $inputFormat = "json";
    public $outputFormat = "auto";
    public $inputField = 1;
    public $justTokenize = false;
    public $modelFilename;
    public $numTokensCorrect = 0;
    public $numTokens = 0;
    public $oovTokensCorrect = 0;
    public $oovTokens = 0;
    public $decoder = "VITERBI";

    public function runTagger($inputFilename) {
        $jsonTweetReader = new JsonTweetReader();
        $lines = file($inputFilename, FILE_IGNORE_NEW_LINES);
        $numtoks = 0;
        
        foreach ($lines as $line) {
            $parts = explode("\t", $line);
            $tweetData = $parts[$this->inputField - 1];
            $text = $this->inputFormat === "json" ? $jsonTweetReader->getText($tweetData) : $tweetData;

            $sentence = new Sentence();
            $sentence->tokens = Twokenize::tokenizeRawTweetText($text);
            $modelSentence = new ModelSentence(count($sentence->tokens));

            if (count($sentence->tokens) > 0 && !$this->justTokenize) {
                $this->goDecode($modelSentence);
            }
            
            $numtoks += count($sentence->tokens);
        }
        
        echo "Processed {$numtoks} tokens.\n";
    }

    public function goDecode($mSent) {
        if ($this->decoder === "GREEDY") {
            // Placeholder for greedy decode
        } else {
            // Placeholder for Viterbi decode
        }
    }

    public static function main($args) {
        if (count($args) == 0) {
            die("Usage: php RunTagger.php <InputFilename>");
        }

        $tagger = new RunTagger();
        $tagger->modelFilename = isset($args[1]) ? $args[1] : null;
        $inputFilename = $args[0];
        
        $tagger->runTagger($inputFilename);
    }
}

RunTagger::main($argv);
--EXPECT--
