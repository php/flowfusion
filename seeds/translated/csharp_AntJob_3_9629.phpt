--TEST--
Translated seed (inferredbugs-csharp: AntJob/3)
--FILE--
<?php

class App {
    public $ID;
    public $Name;
    public $Secret;
    public $Enable;
    public $Version;
    public $CompileTime;
    public $Jobs;

    public static function FindByName($name) {
        // Simulate finding an app by name
        $app = new App();
        $app->ID = 1;
        $app->Name = $name;
        $app->Secret = md5('secret'); // Example secret
        $app->Enable = true;
        $app->Version = '1.0';
        $app->CompileTime = time();
        $app->Jobs = [];
        return $app;
    }

    public function Save() {
        // Simulates saving the app state
    }
}

class Session {
    public $data = [];

    public function __get($name) {
        return $this->data[$name] ?? null;
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }
}

function WriteLog($format, ...$args) {
    echo vsprintf($format, $args) . PHP_EOL;
}

function Login($user, $pass, $session) {
    if (empty($user)) throw new InvalidArgumentException('User cannot be null or empty');

    $app = App::FindByName($user);
    if ($app === null || $app->Secret !== md5($pass)) {
        throw new OutOfBoundsException("App [$user] does not exist!");
    }

    if (!$app->Enable) throw new Exception("Application is disabled!");

    $app->Version = '1.1';
    $app->CompileTime = time();
    $app->Save();

    // Record current user
    $session->App = $app;

    WriteLog("User [%s] logged in successfully", $user);

    return [
        'name' => $app->Name,
        'display_name' => $app->Name,
    ];
}

function main() {
    $session = new Session();
    $user = 'TestApp';
    $pass = 'secret';

    try {
        $result = Login($user, $pass, $session);
        print_r($result);
    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}

main();
--EXPECT--
