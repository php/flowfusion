--TEST--
Translated seed (inferredbugs-csharp: slskd/3)
--FILE--
<?php
$version = PHP_VERSION . ' (' . PHP_SAPI . ')';
$padding = 56 - strlen($version);
$paddingLeft = intdiv($padding, 2);
$paddingRight = $paddingLeft + ($padding % 2);
$centeredVersion = str_repeat(' ', max(0, $paddingLeft)) . $version . str_repeat(' ', max(0, $paddingRight));
$logos = [
"
                ▄▄▄▄               ▄▄▄▄          ▄▄▄▄
     ▄▄▄▄▄▄▄    █  █    ▄▄▄▄▄▄▄    █  █▄▄▄    ▄▄▄█  █
     █__ --█    █  █    █__ --█    █    ◄█    █  -  █
     █▄▄▄▄▄█    █▄▄█    █▄▄▄▄▄█    █▄▄█▄▄█    █▄▄▄▄▄█",
"
                     ▄▄▄▄     ▄▄▄▄     ▄▄▄▄
               ▄▄▄▄▄▄█  █▄▄▄▄▄█  █▄▄▄▄▄█  █
               █__ --█  █__ --█    ◄█  -  █
               █▄▄▄▄▄█▄▄█▄▄▄▄▄█▄▄█▄▄█▄▄▄▄▄█",
];
$logo = $logos[random_int(0, count($logos) - 1)];
echo "
$logo
╒════════════════════════════════════════════════════════╕
│           GNU AFFERO GENERAL PUBLIC LICENSE            │
│                   https://slskd.org                    │
│                                                        │
│$centeredVersion│
└────────────────────────────────────────────────────────┘
";
?>
--EXPECT--
