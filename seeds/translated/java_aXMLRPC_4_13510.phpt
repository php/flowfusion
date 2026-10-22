--TEST--
Translated seed (inferredbugs-java: aXMLRPC/4)
--FILE--
<?php
date_default_timezone_set('UTC');

$dates = [
    "19850304" => "1985-03-04",
    "1985-03-04" => "1985-03-04",
    "1985-03" => "1985-03-01",
    "1985" => "1985-01-01",
    "19850304T122136" => "1985-03-04 12:21:36",
    "1985-03-04T12:21:36" => "1985-03-04 12:21:36",
    "1985-03-04T12" => "1985-03-04 12:00:00",
    "1985-03-04T12:10" => "1985-03-04 12:10:00",
    "1985-03-04T12:21.25" => "1985-03-04 12:21:15",
    "1985-03-04T12:21.5" => "1985-03-04 12:21:30",
    "1985-03-04T12.25" => "1985-03-04 12:15:00",
    "1985-03-04T12:01" => "1985-03-04 12:00:36",
    "1985-03-04T12:21:36.5" => "1985-03-04 12:21:36.5",
    "1985-03-04T12:21:36Z" => "1985-03-04 12:21:36",
    "19850304T131031+1130" => "1985-03-04 01:40:31",
    "19850304T131031+1130" => "1985-03-04 01:40:31",
    "1985-03-04T13:10:31+11:30" => "1985-03-04 13:10:31",
    "1985-03-04T13:10:31+11" => "1985-03-04 13:10:31",
    "1985-03-04T13:10:31-09:20" => "1985-03-04 22:30:31",
    "1980-01-01T00:00:00.0Z" => "1980-01-01 00:00:00",
    "1980-01-01T00Z" => "1980-01-01 00:00:00",
    "1981-12-31T23:59:59Z" => "1981-12-31 23:59:59"
];

foreach ($dates as $input => $expected) {
    $date = DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $input) ?: DateTime::createFromFormat('Y-m-d\TH:i:sP', $input) ?: DateTime::createFromFormat('Y-m-d\TH:i:s', $input) ?: DateTime::createFromFormat('Y-m-d', $input);
    $dateString = $date ? $date->format('Y-m-d H:i:s.u') : 'false';
}

date_default_timezone_set('America/Los_Angeles');

foreach ($dates as $input => $expected) {
    $date = DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $input) ?: DateTime::createFromFormat('Y-m-d\TH:i:sP', $input) ?: DateTime::createFromFormat('Y-m-d\TH:i:s', $input) ?: DateTime::createFromFormat('Y-m-d', $input);
    $dateString = $date ? $date->format('Y-m-d H:i:s.u') : 'false';
}
--EXPECT--
