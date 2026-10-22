--TEST--
Translated seed (inferredbugs-csharp: Disqord/1)
--FILE--
<?php

class MemberUpdatedEventArgs {
    public $oldMember;
    public $newMember;

    public function __construct($oldMember, $newMember) {
        $this->oldMember = $oldMember;
        $this->newMember = $newMember;
    }
}

class CachedMember {
    public $sharedUser;

    public function clone() {
        $clone = new CachedMember();
        $clone->sharedUser = $this->sharedUser;
        return $clone;
    }

    public function update($model) {
        // Simulate updating the member with model data
    }
}

class TransientMember {
    public function __construct($client, $guildId, $model) {
        // Simulate transient member creation
    }
}

class CacheProvider {
    public static function tryGetMembers($guildId, &$cache) {
        // Simulate cache retrieval logic
        $cache = [];
        return false;
    }
}

function handleDispatch($guildId, $model) {
    $oldMember = null;
    $newMember = null;

    if (CacheProvider::tryGetMembers($guildId, $cache) && isset($cache[$model['user']['id']])) {
        $member = $cache[$model['user']['id']];
        $newMember = $member;
        $oldUser = clone $member->sharedUser;
        $oldMember = $member->clone();
        $oldMember->sharedUser = $oldUser;
        $newMember->update($model);
    } else {
        $oldMember = null;
        $newMember = new TransientMember(null, $guildId, $model);
    }

    $event = new MemberUpdatedEventArgs($oldMember, $newMember);
}

// Example input model
$model = [
    'user' => ['id' => '123'],
];

handleDispatch('guild123', $model);
--EXPECT--
