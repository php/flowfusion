--TEST--
Translated seed (inferredbugs-java: LuckyFrameWeb/1)
--FILE--
<?php
class TestJobs {
    private $id;
    private $planproj;
    private $taskName;
    private $clientip;

    public function __construct($id, $planproj, $taskName, $clientip) {
        $this->id = $id;
        $this->planproj = $planproj;
        $this->taskName = $taskName;
        $this->clientip = $clientip;
    }

    public function getId() {
        return $this->id;
    }

    public function getPlanproj() {
        return $this->planproj;
    }

    public function getTaskName() {
        return $this->taskName;
    }

    public function getClientip() {
        return $this->clientip;
    }
}

class QueueListener {
    public static $list = [];
}

class RunTaskEntity {
    private $projectname;
    private $taskid;

    public function setProjectname($projectname) {
        $this->projectname = $projectname;
    }

    public function setTaskid($taskid) {
        $this->taskid = $taskid;
    }
}

class RunService {
    public function runtask($task) {
        return "Task " . $task->projectname . " with ID " . $task->taskid . " executed.";
    }
}

function toRunTask($projname, $jobId, $jobname, $clientip) {
    $tastName = "[" . $jobname . "]_" . date("YmdHis");
    $result = "启动失败！";

    $projname = str_replace(" ", "\" \"", $projname);
    
    $tasken = new RunTaskEntity();
    $tasken->setProjectname($projname);
    $tasken->setTaskid($jobId);

    $service = new RunService();
    
    try {
        $result = $service->runtask($tasken);
        echo $result;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function execute() {
    $id = "1";
    $job = null;

    for ($i = 0; $i < count(QueueListener::$list); $i++) {
        $job = QueueListener::$list[$i];
        if ($id === (string)$job->getId()) {
            break;
        }
    }
    
    toRunTask($job->getPlanproj(), $job->getId(), $job->getTaskName(), $job->getClientip());
}

QueueListener::$list[] = new TestJobs(1, "Test Project", "Test Task", "127.0.0.1");
execute();
--EXPECT--
