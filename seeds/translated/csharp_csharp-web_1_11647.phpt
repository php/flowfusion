--TEST--
Translated seed (inferredbugs-csharp: csharp-web/1)
--FILE--
<?php
class User {
    public string $username;
    public function __construct(string $username) { $this->username = $username; }
}

class HomeController {
    private ?User $user;

    public function __construct(?User $user = null) {
        $this->user = $user;
    }

    public function indexSlash() {
        return $this->index();
    }

    public function index() {
        if ($this->isLoggedIn()) {
            return ['view' => 'Home', 'model' => ['Username' => $this->user->username]];
        }
        return ['view' => null, 'model' => null];
    }

    public function test(iterable $list) {
        return ['view' => null, 'model' => null];
    }

    private function isLoggedIn(): bool {
        return $this->user !== null;
    }
}

$controller = new HomeController(new User("alice"));
$controller->indexSlash();
$controller->test(["a", "b", "c"]);
$controller = new HomeController();
$controller->index();
$controller->test(new ArrayIterator(["x", "y"]));
--EXPECT--
