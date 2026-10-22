--TEST--
Translated seed (inferredbugs-java: X-SpringBoot/3)
--FILE--
<?php

class AuthenticationTokenFilter {
    private $redis;
    
    public function __construct($redis) {
        $this->redis = $redis;
    }

    public function doFilter($token) {
        if (!empty($token)) {
            $key = serialize($token);
            $value = $this->redis[$key] ?? null;
            if ($value) {
                $authentication = unserialize($value);
                // Simulate setting authentication context
                // In PHP, we don't have an exact equivalent of SecurityContextHolder
            }
        }
    }
}

$redis = [];
$filter = new AuthenticationTokenFilter($redis);
$token = "exampleToken";
$redis[serialize($token)] = serialize("authData"); // Simulating redis value
$filter->doFilter($token);
--EXPECT--
