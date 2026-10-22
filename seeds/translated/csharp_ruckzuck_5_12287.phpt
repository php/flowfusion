--TEST--
Translated seed (inferredbugs-csharp: ruckzuck/5)
--FILE--
<?php
class DLTask {
    public bool $AutoInstall = false;
    public bool $Installed = false;
    public bool $UnInstalled = false;
    public bool $Downloading = false;
    public bool $Installing = false;
    public bool $Error = false;
    public bool $WaitingForDependency = false;
    public string $ErrorMessage = '';
    public string $_status = '';
    public int $PercentDownloaded = 0;
    public function __get($n) {
        if ($n === 'Status') {
            if ($this->_status !== '') return $this->_status;
            if ($this->Installing && !$this->Error) return 'Installing';
            if ($this->Downloading && !$this->Error) return 'Downloading';
            if ($this->Installed && !$this->Error) return 'Installed';
            if ($this->UnInstalled && !$this->Error) return 'Uninstalled';
            if ($this->WaitingForDependency) return 'Installing dependencies';
            if ($this->PercentDownloaded == 100 && !$this->Error) return 'Downloaded';
            if ($this->Error) return $this->ErrorMessage;
            return 'Waiting';
        }
        return null;
    }
    public function __set($n, $v) {
        if ($n === 'Status') $this->_status = $v;
    }
}

@mkdir('wwwroot', 0777, true);
@mkdir('wwwroot/icons', 0777, true);
@mkdir('wwwroot/files', 0777, true);

file_put_contents('wwwroot/icons/42.jpg', "icon");
file_put_contents('wwwroot/files/a/b.txt', "file");

$t = new DLTask();
$t->PercentDownloaded = 100;
echo $t->Status, "\n";
$t->Downloading = true;
echo $t->Status, "\n";
$t->Status = "custom";
echo $t->Status, "\n";

$s = "A\0B/\\C:Δ";
$norm = preg_replace('/[^[:alnum:]]+/u', '', $s);
echo $norm, "\n";

$path = str_replace('\\', '/', 'a\\b.txt');
echo file_get_contents('wwwroot/files/a/b.txt'), "\n";
echo file_get_contents('wwwroot/icons/42.jpg'), "\n";
?>
--EXPECT--
