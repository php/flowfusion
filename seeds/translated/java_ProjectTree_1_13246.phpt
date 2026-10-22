--TEST--
Translated seed (inferredbugs-java: ProjectTree/1)
--FILE--
<?php

$map = [];
$threadId = getmyuid();

function before($identify) {
    global $map, $threadId;
    $methodNode = getMethodNode($identify, $threadId);
    pushMap($methodNode);
}

function after($identify, $returnType) {
    global $map, $threadId;
    popMap($identify, $threadId, $returnType);
}

function pushMap($methodNode) {
    global $map;
    $id = (string)$methodNode['threadId'];
    if (!isset($map[$id])) {
        $map[$id] = [];
    }
    array_push($map[$id], $methodNode);
}

function popMap($identify, $threadId, $returnType) {
    global $map;
    $id = (string)$threadId;
    if (!isset($map[$id]) || empty($map[$id])) {
        return;
    }
    $methodNode = array_pop($map[$id]);
    while ($methodNode['identify'] !== $identify) {
        $methodNode = array_pop($map[$id]);
    }
    $methodNode['endTime'] = new DateTime();
    $methodNode['returnType'] = $returnType;
    setMembership($map[$id], $methodNode);
}

function setMembership(&$stack, $methodNode) {
    if (!empty($stack)) {
        $node = array_pop($stack);
        $list = $node['methodNodes'] ?? [];
        $list[] = $methodNode;
        $node['methodNodes'] = $list;
        array_push($stack, $node);
    } else {
        $methodNode['first'] = true;
    }
    save($methodNode);
}

function save($methodNode) {
    // Simulated save operation
}

function getMethodNode($identify, $threadId) {
    return ['identify' => $identify, 'threadId' => $threadId];
}

before(1);
after(1, 'void');
--EXPECT--
