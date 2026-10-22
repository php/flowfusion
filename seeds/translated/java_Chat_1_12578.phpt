--TEST--
Translated seed (inferredbugs-java: Chat/1)
--FILE--
<?php

class MessageHandler {
    public function handle($user, $message, $targetUser, $metadata) {
        // Simulate a potential bug by processing null values
        if (is_null($user) || is_null($message)) {
            echo "Handling null user or message\n";
        } else {
            echo "User: $user, Message: $message\n";
        }
    }
}

class SpringContextUtil {
    public static function getBean($name, $type) {
        // Return a new instance of MessageHandler regardless of the parameters
        return new MessageHandler();
    }
}

// Main entry point
$messageHandler = SpringContextUtil::getBean("MessageHandler", "login");
$messageHandler->handle(null, null, null, null);
--EXPECT--
