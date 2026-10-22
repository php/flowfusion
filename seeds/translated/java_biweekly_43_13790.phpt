--TEST--
Translated seed (inferredbugs-java: biweekly/43)
--FILE--
<?php

$defaultTz = date_default_timezone_get();
date_default_timezone_set('UTC');

$xml = 
"<?xml version=\"1.0\" encoding=\"utf-8\" ?>" .
"<icalendar xmlns=\"http://example.com\">" .
    "<vcalendar>" .
        "<properties>" .
            "<prodid><text>-//Example Inc.//Example Client//EN</text></prodid>" .
            "<version><text>2.0</text></version>" .
        "</properties>" .
        "<components>" .
            "<vevent>" .
                "<properties>" .
                    "<summary><text>Team Meeting</text></summary>" .
                "</properties>" .
            "</vevent>" .
        "</components>" .
    "</vcalendar>" .
    "<vcalendar>" .
    "<properties>" .
        "<prodid><text>-//Example Inc.//Example Client//EN</text></prodid>" .
        "<version><text>2.0</text></version>" .
    "</properties>" .
    "<components>" .
        "<vevent>" .
            "<properties>" .
                "<summary><text>Team Happy Hour</text></summary>" .
            "</properties>" .
        "</vevent>" .
    "</components>" .
"</vcalendar>" .
"</icalendar>";

$xcal = new SimpleXMLElement($xml);
$parseWarnings = [];
$calendars = [];

foreach ($xcal->vcalendar as $vcalendar) {
    $prodid = (string) $vcalendar->properties->prodid->text;
    $version = (string) $vcalendar->properties->version->text;

    if ($prodid !== "-//Example Inc.//Example Client//EN" || $version !== "2.0") {
        $parseWarnings[] = 1;
    }

    foreach ($vcalendar->components->vevent->properties->summary->text as $summary) {
        $calendars[] = (string) $summary;
    }
}

if (count($parseWarnings) > 0) {
    foreach ($parseWarnings as $warning) {
        // Handle warnings if needed
    }
}

date_default_timezone_set($defaultTz);
--EXPECT--
