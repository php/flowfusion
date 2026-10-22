--TEST--
Translated seed (inferredbugs-csharp: OSS.Core/3)
--FILE--
<?php
declare(strict_types=1);

class CoreCookieKeys {
    public const UserCookieName = 'user_cookie';
}

class AppSourceMode {
    public const Browser = 1;
}

class CoreAppContext {
    public static array $Identity = ['source_mode' => 1, 'token' => null];
}

class PortalAuthHelper {
    public static function GetCookie(array $req): string {
        return $req['cookies'][CoreCookieKeys::UserCookieName] ?? '';
    }

    public static function GetMyself(array $req): array {
        $appinfo = &CoreAppContext::$Identity;
        if ($appinfo['source_mode'] >= AppSourceMode::Browser) {
            $appinfo['token'] = self::GetCookie($req);
        }
        return ['ok' => true, 'identity' => $appinfo];
    }

    public static function SetCookie(array &$response, string $token): void {
        $response['cookies'][CoreCookieKeys::UserCookieName] = [
            'value' => $token,
            'httponly' => true,
            'expires' => time() + 30 * 86400,
        ];
    }

    public static function ClearCookie(array &$response): void {
        unset($response['cookies'][CoreCookieKeys::UserCookieName]);
    }
}

$req = ['cookies' => [CoreCookieKeys::UserCookieName => 'abc123']];
$response = ['cookies' => []];

PortalAuthHelper::SetCookie($response, 'token1');
PortalAuthHelper::ClearCookie($response);
$result = PortalAuthHelper::GetMyself($req);

echo $result['identity']['token'];
?>
--EXPECT--
