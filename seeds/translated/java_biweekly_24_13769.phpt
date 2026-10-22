--TEST--
Translated seed (inferredbugs-java: biweekly/24)
--FILE--
<?php

class RequestStatus {
    private $statusCode;
    private $description;
    private $exceptionText;

    public function __construct($statusCode) {
        $this->statusCode = $statusCode;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setExceptionText($exceptionText) {
        $this->exceptionText = $exceptionText;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getExceptionText() {
        return $this->exceptionText;
    }
}

class RequestStatusMarshaller {
    public function writeText(RequestStatus $prop) {
        $parts = [];
        if ($prop->getStatusCode() !== null) {
            $parts[] = $prop->getStatusCode();
        }
        if ($prop->getDescription() !== null) {
            $parts[] = str_replace(";", "\\;", $prop->getDescription());
        }
        if ($prop->getExceptionText() !== null) {
            $parts[] = str_replace(";", "\\;", $prop->getExceptionText());
        }
        return implode(";", $parts);
    }
}

$marshaller = new RequestStatusMarshaller();

$prop = new RequestStatus(null);
echo $marshaller->writeText($prop) . "\n";

$prop = new RequestStatus("1.2.3");
echo $marshaller->writeText($prop) . "\n";

$prop = new RequestStatus("1.2.3");
$prop->setDescription("description;here");
echo $marshaller->writeText($prop) . "\n";

$prop = new RequestStatus("1.2.3");
$prop->setDescription("description;here");
$prop->setExceptionText("data;here");
echo $marshaller->writeText($prop) . "\n";

$prop = new RequestStatus("1.2.3");
$prop->setDescription(null);
$prop->setExceptionText("data;here");
echo $marshaller->writeText($prop) . "\n";

$prop = new RequestStatus(null);
$prop->setDescription("description;here");
$prop->setExceptionText("data;here");
echo $marshaller->writeText($prop) . "\n";
--EXPECT--
