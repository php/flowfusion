--TEST--
Translated seed (inferredbugs-csharp: MailMergeLib/1)
--FILE--
<?php
declare(strict_types=1);

class SenderConfig { public string $value = ''; }
class MessageConfig { public string $value = ''; }

class Settings {
    public SenderConfig $SenderConfig;
    public MessageConfig $MessageConfig;
    public static string $CryptoKey = '';
    public static bool $CryptoEnabled = false;

    public function __construct() {
        $this->SenderConfig = new SenderConfig();
        $this->MessageConfig = new MessageConfig();
    }

    public function serializeToString(): string {
        $x = new SimpleXMLElement('<MailMergeLibSettings/>');
        $x->addChild('Sender', htmlspecialchars($this->SenderConfig->value, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        $x->addChild('Message', htmlspecialchars($this->MessageConfig->value, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        if (self::$CryptoEnabled) $x->addChild('CryptoKey', htmlspecialchars(self::$CryptoKey, ENT_XML1 | ENT_COMPAT, 'UTF-8'));
        return $x->asXML();
    }

    public static function deserializeString(string $xml): self {
        $x = simplexml_load_string($xml);
        $s = new self();
        if ($x !== false) {
            $s->SenderConfig->value = (string)($x->Sender ?? '');
            $s->MessageConfig->value = (string)($x->Message ?? '');
            if (isset($x->CryptoKey)) self::$CryptoKey = (string)$x->CryptoKey;
        }
        return $s;
    }
}

$settings = new Settings();
$settings->SenderConfig->value = str_repeat("A", 1024) . "&<>'\"";
$settings->MessageConfig->value = "line1\nline2\r\n" . str_repeat("𝄞", 128);
Settings::$CryptoEnabled = true;
Settings::$CryptoKey = str_repeat("k", 1) . "\0" . str_repeat("z", 3);

$xml = $settings->serializeToString();
$roundtrip = Settings::deserializeString($xml);
echo $roundtrip->serializeToString();
?>
--EXPECT--
