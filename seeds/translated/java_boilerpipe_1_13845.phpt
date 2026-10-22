--TEST--
Translated seed (inferredbugs-java: boilerpipe/1)
--FILE--
<?php

class BoilerpipeProcessingException extends Exception {}

class ExtractorBase {

    public function getText($input) {
        if ($input instanceof TextDocument) {
            return $this->getTextFromDocument($input);
        } elseif (is_string($input)) {
            return $this->getTextFromString($input);
        } elseif ($input instanceof InputSource) {
            return $this->getTextFromInputSource($input);
        }
        throw new BoilerpipeProcessingException("Invalid input type");
    }

    private function getTextFromString($html) {
        return $this->getText(new InputSource(new StringReader($html)));
    }

    private function getTextFromInputSource($is) {
        return $this->getTextFromDocument((new BoilerpipeSAXInput($is))->getTextDocument());
    }

    private function getTextFromDocument($doc) {
        $this->process($doc);
        return $doc->getContent();
    }

    private function process($doc) {
        // Simulated processing logic
        $doc->content = strip_tags($doc->content);
    }

}

class InputSource {
    public $reader;
    
    public function __construct($reader) {
        $this->reader = $reader;
    }
}

class StringReader {
    private $string;
    
    public function __construct($string) {
        $this->string = $string;
    }
}

class BoilerpipeSAXInput {
    private $inputSource;
    
    public function __construct($inputSource) {
        $this->inputSource = $inputSource;
    }
    
    public function getTextDocument() {
        return new TextDocument($this->inputSource->reader->string);
    }
}

class TextDocument {
    public $content;
    
    public function __construct($html) {
        $this->content = $html;
    }
    
    public function getContent() {
        return $this->content;
    }
}

$extractor = new ExtractorBase();
$html = '<html><body><p>Hello World!</p></body></html>';
$text = $extractor->getText($html);
echo $text;
--EXPECT--
