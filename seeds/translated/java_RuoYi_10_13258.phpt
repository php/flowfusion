--TEST--
Translated seed (inferredbugs-java: RuoYi/10)
--FILE--
<?php

class ShiroUtils
{
    public static function randomSalt()
    {
        return bin2hex(random_bytes(3));
    }
}

echo ShiroUtils::randomSalt();
--EXPECT--
