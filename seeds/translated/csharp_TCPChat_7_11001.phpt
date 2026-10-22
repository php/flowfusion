--TEST--
Translated seed (inferredbugs-csharp: TCPChat/7)
--FILE--
<?php
class ClientModel {
    private static ?ClientModel $model = null;
    public static ?object $API = null;
    public static ?object $Client = null;
    public static ?object $Peer = null;
    public static ?object $Plugins = null;

    public array $Rooms;
    public array $DownloadingFiles;
    public array $PostedFiles;
    public ?object $User = null;

    public function __construct() {
        $this->Rooms = [];
        $this->DownloadingFiles = [];
        $this->PostedFiles = [];
    }

    public static function Get(): ClientModel {
        if (self::$model === null) {
            throw new InvalidArgumentException("model do not inited yet");
        }
        return self::$model;
    }

    public static function Init(object $initializer): void {
        if (self::$model !== null) {
            throw new InvalidArgumentException("model already inited");
        }
        self::$model = new ClientModel();
        self::$model->User = (object)['Nick' => $initializer->Nick, 'NickColor' => $initializer->NickColor];
        self::$Client = (object)['Nick' => $initializer->Nick];
        self::$Peer = (object)[];
        self::$Plugins = (object)['Path' => $initializer->PluginsPath, 'Excluded' => $initializer->ExcludedPlugins];
    }

    public static function Reset(): void {
        if (self::$model === null) {
            throw new InvalidArgumentException("model not yet inited");
        }
        self::$model = null;
        self::$Plugins = null;
        self::$Client = null;
        self::$Peer = null;
        self::$API = null;
    }
}

$init = (object)[
    'Nick' => 'nick',
    'NickColor' => '#fff',
    'PluginsPath' => '',
    'ExcludedPlugins' => []
];

try { ClientModel::Get(); } catch (Throwable $e) {}
ClientModel::Init($init);
$m = ClientModel::Get();
$m->Rooms['a'] = (object)['Name' => 'room'];
$m->DownloadingFiles[] = (object)['Id' => 1];
$m->PostedFiles[] = (object)['Id' => 2];
ClientModel::Reset();
try { ClientModel::Reset(); } catch (Throwable $e) {}
?>
--EXPECT--
