--TEST--
Translated seed (inferredbugs-csharp: Chia-Plot-Status/1)
--FILE--
<?php

class UpdateDialog
{
    public $language;
    public $latest;
    public $current;

    public function __construct($language)
    {
        $this->language = $language;
        $this->current = $this->loadCurrentRelease();
        $this->latest = $this->loadLatestRelease();
        $this->checkForUpdates();
    }

    private function loadLatestRelease()
    {
        $latest = null;
        $url = "https://api.github.com/repos/grayfallstown/Chia-Plot-Status/releases";
        $options = [
            "http" => [
                "header" => "User-Agent: PHP",
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result !== false) {
            $releases = json_decode($result, true);
            if (isset($releases[0])) {
                $latest = $releases[0];
            }
        }
        return $latest;
    }

    private function loadCurrentRelease()
    {
        $yaml = file_get_contents("version.yaml");
        $versionData = yaml_parse($yaml);
        return $versionData['Version'] ?? '';
    }

    private function checkForUpdates()
    {
        if ($this->latest['tag_name'] === $this->current) {
            echo "Up to date.\n";
        } else {
            echo "Update available: " . $this->latest['html_url'] . "\n";
        }
    }

    public function downloadUpdate()
    {
        $url = str_replace("/tag/", "/download/", $this->latest['html_url']) . "/Setup.exe";
        $this->openUrl($url);
    }

    private function openUrl($url)
    {
        exec("start " . escapeshellarg($url));
    }
}

$dialog = new UpdateDialog("English");
$dialog->downloadUpdate();
--EXPECT--
