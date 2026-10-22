--TEST--
Translated seed (inferredbugs-java: cloudsim-plus/10)
--FILE--
<?php

$SCHEDULING_INTERVAL = 5;
$CLOUDLET_LENGTHS = [1000, 2000, 3000];
$CLOUDLET_PES = [1, 2, 4];
$VM_PES = [1, 2, 4];
$MIPS_VM = [1000, 2000, 3000];
$CLOUDLETS = 10;

function sampleUniformDistribution($max) {
    return rand(0, $max - 1);
}

function createCloudlet($broker) {
    global $CLOUDLET_LENGTHS, $CLOUDLET_PES;
    $length = $CLOUDLET_LENGTHS[sampleUniformDistribution(count($CLOUDLET_LENGTHS))];
    $pes = $CLOUDLET_PES[sampleUniformDistribution(count($CLOUDLET_PES))];
    return ['length' => $length, 'pes' => $pes, 'broker' => $broker];
}

function createCloudlets($broker) {
    global $CLOUDLETS;
    $cloudletList = [];
    for ($i = 0; $i < $CLOUDLETS; $i++) {
        $cloudletList[] = createCloudlet($broker);
    }
    return $cloudletList;
}

function getExpectedCloudletCompletionTime($cloudlet, $vm) {
    return $cloudlet['length'] / $vm['mips'];
}

function selectVmForCloudlet($cloudlet, $execVms) {
    $mostFreePesVm = null;
    $minimumTime = PHP_INT_MAX;
    foreach ($execVms as $vm) {
        $completionTime = getExpectedCloudletCompletionTime($cloudlet, $vm);
        if ($completionTime < $minimumTime) {
            $minimumTime = $completionTime;
            $mostFreePesVm = $vm;
        }
    }
    return $mostFreePesVm;
}

function createVm($id) {
    global $VM_PES, $MIPS_VM;
    $pes = $VM_PES[sampleUniformDistribution(count($VM_PES))];
    $mips = $MIPS_VM[sampleUniformDistribution(count($MIPS_VM))];
    return ['id' => $id, 'mips' => $mips, 'pes' => $pes];
}

$broker = 'Broker1';
$cloudlets = createCloudlets($broker);
$execVms = [createVm(1), createVm(2)];
foreach ($cloudlets as $cloudlet) {
    $selectedVm = selectVmForCloudlet($cloudlet, $execVms);
    echo "Cloudlet of length {$cloudlet['length']} assigned to VM with ID {$selectedVm['id']}\n";
}
--EXPECT--
