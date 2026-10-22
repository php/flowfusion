--TEST--
Translated seed (inferredbugs-java: NettyGameServer/9)
--FILE--
<?php

$lock = new stdClass();
$sdWorldServers = [];
$sdGameServers = [];
$sdDbServers = [];

function initWorldConnectedServer() {
    // Simulate initialization logic...
}

function initGameConnectedServer() {
    // Simulate initialization logic...
}

function initDbConnectServer() {
    // Simulate initialization logic...
}

function loadServers($element, &$serverList) {
    // Simulate loading server logic...
    $serverList[] = new stdClass(); // Adding dummy server objects
}

function init() {
    global $sdWorldServers, $sdGameServers, $sdDbServers, $lock;

    $rootElement = new stdClass(); // Simulate XML root element
    $rootElement->world = new stdClass();
    $rootElement->game = new stdClass();
    $rootElement->db = new stdClass();

    loadServers($rootElement->world, $sdWorldServers);
    loadServers($rootElement->game, $sdGameServers);
    loadServers($rootElement->db, $sdDbServers);

    // Simulate synchronization
    $lock; // Using lock for synchronization

    initWorldConnectedServer();
    initGameConnectedServer();
    initDbConnectServer();
}

init();
--EXPECT--
