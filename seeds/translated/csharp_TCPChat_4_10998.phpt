--TEST--
Translated seed (inferredbugs-csharp: TCPChat/4)
--FILE--
<?php
class ServerModel {
    public static $model = null;
    public static $Server = null;
    public static $API = null;
    public static $Plugins;
    public static $Notifier;
    public $Rooms;
    public $Users;
    const MainRoomName = "Main room";
    public function __construct() {
        $this->Users = [];
        $this->Rooms = [];
        $this->Rooms[self::MainRoomName] = (object)["parent" => null, "name" => self::MainRoomName];
    }
    public static function Init($initializer) {
        if (self::$model !== null) throw new Exception("model already inited");
        self::$model = new self();
        self::$Server = new stdClass();
        self::$API = isset($initializer->API) && $initializer->API !== null ? $initializer->API : new stdClass();
        self::$Plugins = new stdClass();
        self::$Notifier = new stdClass();
    }
    public static function Reset() {
        if (self::$model === null) throw new Exception("model not yet inited");
        self::$model = null;
        self::$Server = null;
        self::$API = null;
    }
    public static function Get() {
        if (self::$model === null) throw new Exception("model do not inited yet");
        return self::$model;
    }
}
$initializer = (object)["API" => null, "PluginsPath" => null, "ExcludedPlugins" => []];
ServerModel::Init($initializer);
$m = ServerModel::Get();
$room = $m->Rooms[ServerModel::MainRoomName];
ServerModel::Reset();
try { ServerModel::Get(); } catch (Exception $e) {}
try { ServerModel::Reset(); } catch (Exception $e) {}
?>
--EXPECT--
