--TEST--
Translated seed (inferredbugs-java: cloudsim-plus/15)
--FILE--
<?php

class Cloudlet {
    private $id;
    private $length;
    private $numProc;
    private $submissionDelay;

    public function __construct($id, $length, $numProc) {
        $this->id = $id;
        $this->length = $length;
        $this->numProc = $numProc;
    }

    public function setSubmissionDelay($delay) {
        $this->submissionDelay = $delay;
    }
}

class SwfWorkloadFileReader {
    private $mips;
    private $cloudlets = [];
    private $predicate;

    public function __construct($mips) {
        $this->setMips($mips);
        $this->predicate = function() { return true; };
    }

    public function generateWorkload($lines) {
        foreach ($lines as $line) {
            $this->createCloudletFromTraceLine(explode(" ", $line));
        }
        return $this->cloudlets;
    }

    private function createCloudletFromTraceLine($parsedLineArray) {
        if (count($parsedLineArray) < 18) {
            return false;
        }

        $id = count($this->cloudlets) + 1;
        $runTime = max((int)trim($parsedLineArray[3]), 1);
        $maxNumProc = max((int)trim($parsedLineArray[7]), (int)trim($parsedLineArray[4]));
        $numProc = max($maxNumProc, 1);
        
        $cloudlet = new Cloudlet($id, $runTime * $this->mips, $numProc);
        $cloudlet->setSubmissionDelay((int)trim($parsedLineArray[1]));

        if (call_user_func($this->predicate, $cloudlet)) {
            $this->cloudlets[] = $cloudlet;
            return true;
        }
        
        return false;
    }

    public function setMips($mips) {
        if ($mips <= 0) {
            throw new InvalidArgumentException("MIPS must be greater than 0.");
        }
        $this->mips = $mips;
        return $this;
    }
}

$lines = [
    "1 0 0 100 2 0 0 0 0 0 0 0 0 0 0 0 0 0", // Sample trace lines
    "2 0 0 200 4 0 0 0 0 0 0 0 0 0 0 0 0 0"
];

$reader = new SwfWorkloadFileReader(1000);
$reader->generateWorkload($lines);
--EXPECT--
