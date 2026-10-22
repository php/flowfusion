--TEST--
Translated seed (inferredbugs-java: aliyun-oss-java-sdk/2)
--FILE--
<?php

class IniEditor {
    private $sections = [];
    private $sectionOrder = [];
    private $commonName;

    public function addSection($name) {
        if (!isset($this->sections[$name])) {
            $this->sections[$name] = [];
            $this->sectionOrder[] = $name;
            return true;
        }
        return false;
    }

    public function set($section, $option, $value) {
        if (isset($this->sections[$section])) {
            $this->sections[$section][$option] = $value;
        } else {
            throw new Exception("No such section: $section");
        }
    }

    public function get($section, $option) {
        return $this->sections[$section][$option] ?? null;
    }

    public function save($filename) {
        $content = '';
        foreach ($this->sectionOrder as $section) {
            $content .= "[$section]\n";
            foreach ($this->sections[$section] as $option => $value) {
                $content .= "$option = $value\n";
            }
            $content .= "\n";
        }
        file_put_contents($filename, $content);
    }

    public function load($filename) {
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $currentSection = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/^\[(.+)\]$/', $line, $matches)) {
                $currentSection = $matches[1];
                $this->addSection($currentSection);
            } elseif (strpos($line, '=') !== false) {
                [$option, $value] = explode('=', $line, 2);
                $this->set($currentSection, trim($option), trim($value));
            }
        }
    }
}

$editor = new IniEditor();
$editor->load('users.ini');
$editor->set('root', 'last_login', '2003-05-16');
$editor->set('root', 'change_pwd', '10 days');
$editor->save('users.ini');
--EXPECT--
