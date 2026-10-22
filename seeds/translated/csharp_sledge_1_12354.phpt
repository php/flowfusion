--TEST--
Translated seed (inferredbugs-csharp: sledge/1)
--FILE--
<?php
class ProviderNotFoundException extends Exception {}
class GameData {
    public int $MapSizeHigh = 0;
    public int $MapSizeLow = 0;
    public array $Classes = [];
    public function CreateDependencies(): void {}
}
abstract class GameDataProvider {
    private static array $RegisteredProviders = [];
    public static function Register($provider): void { self::$RegisteredProviders[] = $provider; }
    public static function Deregister($provider): void { self::$RegisteredProviders = array_values(array_filter(self::$RegisteredProviders, fn($p) => $p !== $provider)); }
    public static function GetGameDataFromString(string $contents): GameData {
        foreach (self::$RegisteredProviders as $provider) {
            if ($provider->IsValidForString($contents)) {
                $gd = $provider->GetFromString($contents);
                $gd->CreateDependencies();
                return $gd;
            }
        }
        throw new ProviderNotFoundException("No GameData provider was found for this string.");
    }
    protected function IsValidForString(string $contents): bool { return $this->IsValidForStream(fopen('php://memory', 'r+')); }
    abstract protected function IsValidForStream($stream): bool;
    protected function GetFromString(string $contents): GameData { return $this->GetFromStream(fopen('php://memory', 'r+')); }
    abstract protected function GetFromStream($stream): GameData;
}
class DemoProvider extends GameDataProvider {
    protected function IsValidForStream($stream): bool { return true; }
    protected function GetFromStream($stream): GameData {
        $gd = new GameData();
        $gd->MapSizeHigh = 1;
        $gd->MapSizeLow = 2;
        $gd->Classes[] = "x";
        return $gd;
    }
}
GameDataProvider::Register(new DemoProvider());
$gd = GameDataProvider::GetGameDataFromString("abc");
echo $gd->MapSizeHigh . $gd->MapSizeLow . count($gd->Classes);
?>
--EXPECT--
