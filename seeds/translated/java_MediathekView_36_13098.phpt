--TEST--
Translated seed (inferredbugs-java: MediathekView/36)
--FILE--
<?php

class Download {
    public $url;
    public $status;

    const STATUS_PENDING = 0;
    const STATUS_RUNNING = 1;
    const STATUS_COMPLETED = 2;

    public function __construct($url) {
        $this->url = $url;
        $this->status = self::STATUS_PENDING;
    }

    public function start() {
        $this->status = self::STATUS_RUNNING;
    }

    public function complete() {
        $this->status = self::STATUS_COMPLETED;
    }
}

$downloads = [];
$downloads[] = new Download("http://example.com/file1");
$downloads[] = new Download("http://example.com/file2");

function updateDownloads(&$downloads) {
    foreach ($downloads as $download) {
        if ($download->status === Download::STATUS_PENDING) {
            $download->start();
        }
    }
}

function stopDownloads(&$downloads) {
    foreach ($downloads as $download) {
        if ($download->status === Download::STATUS_RUNNING) {
            $download->complete();
        }
    }
}

function cleanUpDownloads(&$downloads) {
    foreach ($downloads as $key => $download) {
        if ($download->status === Download::STATUS_COMPLETED) {
            unset($downloads[$key]);
        }
    }
}

updateDownloads($downloads);
stopDownloads($downloads);
cleanUpDownloads($downloads);

foreach ($downloads as $download) {
    echo "Download URL: {$download->url}, Status: {$download->status}\n";
}
--EXPECT--
