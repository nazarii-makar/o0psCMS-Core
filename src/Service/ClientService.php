<?php

namespace o0psCore\Service;

/**
 * Class ClientService
 * @package o0psCore\Service
 */
/**
 * Class ClientService
 * @package o0psCore\Service
 */
class ClientService
{
    /**
     * @var null
     */
    protected static $client = null;

    /**
     * @return string
     */
    public static function browser()
    {
        $browser = 'Unknown';

        if (preg_match('/MSIE/i', $_SERVER['HTTP_USER_AGENT']) &&
            !preg_match('/Opera/i', $_SERVER['HTTP_USER_AGENT'])
        ) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $_SERVER['HTTP_USER_AGENT'])) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/Chrome/i', $_SERVER['HTTP_USER_AGENT'])) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/Safari/i', $_SERVER['HTTP_USER_AGENT'])) {
            $browser = 'Apple Safari';
        } elseif (preg_match('/Opera/i', $_SERVER['HTTP_USER_AGENT'])) {
            $browser = 'Opera';
        } elseif (preg_match('/Netscape/i', $_SERVER['HTTP_USER_AGENT'])) {
            $browser = 'Netscape';
        }

        return $browser;
    }

    /**
     * @return string
     */
    public static function platform()
    {
        $platform = 'Unknown';

        $os_array = [
            '/windows nt 10/i'      => 'Windows 10',
            '/windows nt 6.3/i'     => 'Windows 8.1',
            '/windows nt 6.2/i'     => 'Windows 8',
            '/windows nt 6.1/i'     => 'Windows 7',
            '/windows nt 6.0/i'     => 'Windows Vista',
            '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     => 'Windows XP',
            '/windows xp/i'         => 'Windows XP',
            '/windows nt 5.0/i'     => 'Windows 2000',
            '/windows me/i'         => 'Windows ME',
            '/win98/i'              => 'Windows 98',
            '/win95/i'              => 'Windows 95',
            '/win16/i'              => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i'        => 'Mac OS 9',
            '/linux/i'              => 'Linux',
            '/ubuntu/i'             => 'Ubuntu',
            '/iphone/i'             => 'iPhone',
            '/ipod/i'               => 'iPod',
            '/ipad/i'               => 'iPad',
            '/android/i'            => 'Android',
            '/blackberry/i'         => 'BlackBerry',
            '/webos/i'              => 'Mobile',
        ];

        foreach ($os_array as $regex => $value) {

            if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
                $platform = $value;
            }

        }

        return $platform;
    }

    /**
     * @return mixed
     */
    public static function language()
    {
        $language = 'Unknown';

        preg_match('/^(?:[\w]+)-(?:[\w]+)/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $language);

        return $language[0];
    }

    /**
     * @return mixed
     */
    public static function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @return mixed
     */
    public static function client()
    {
        $ip = self::ip();

        if (self::$client === null) {
            self::$client = @file_get_contents("http://ipinfo.io/{$ip}/json");
        }

        $client_default = array_fill_keys(['city', 'region', 'country', 'loc'], 'Unknown');

        $client = (array)json_decode(self::$client);

        return array_replace($client_default, $client);
    }

    /**
     * @return mixed
     */
    public static function request()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * @return array
     */
    public static function info()
    {
        $client = self::client();

        return [
            'ip'       => self::ip(),
            'request'  => self::request(),
            'browser'  => self::browser(),
            'platform' => self::platform(),
            'language' => self::language(),
            'city'     => $client['city'],
            'region'   => $client['region'],
            'country'  => $client['country'],
            'loc'      => $client['loc']
        ];
    }
}
