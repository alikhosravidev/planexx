<?php

declare(strict_types=1);

namespace App\Utilities;

class Cookie
{
    public static function deleteFromAllDomains(string $cookieName): void
    {
        $host = parse_url(
            config('app.url'),
            PHP_URL_HOST
        );
        $domains = [$host];

        if (substr_count($host, '.') > 1) {
            $parts = explode('.', $host);
            array_shift($parts);
            $rootDomain = implode('.', $parts);
            $domains[]  = $rootDomain;
        }

        foreach ($domains as $domain) {
            foreach (['/', '/some-path'] as $path) {
                setcookie($cookieName, '', time() - 3600, $path, $domain);
                setcookie($cookieName, '', time() - 3600, $path, '.' . $domain);
            }
        }
    }
}
