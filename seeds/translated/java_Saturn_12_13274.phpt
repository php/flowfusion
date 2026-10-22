--TEST--
Translated seed (inferredbugs-java: Saturn/12)
--FILE--
<?php

function simulateBugPattern() {
    $jobs = [
        "job1" => ["enabled" => true, "dependencies" => ""],
        "job2" => ["enabled" => false, "dependencies" => "job1"],
        "job3" => ["enabled" => true, "dependencies" => "job1, job2"],
    ];
    
    foreach ($jobs as $jobName => $jobConfig) {
        if ($jobConfig['enabled']) {
            $dependencies = explode(',', $jobConfig['dependencies']);
            foreach ($dependencies as $dependency) {
                $dependency = trim($dependency);
                if ($dependency === $jobName) {
                    continue;
                }
                echo "Job '{$jobName}' has dependency on '{$dependency}'\n";
            }
        }
    }
}

simulateBugPattern();
--EXPECT--
