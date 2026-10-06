<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pathcrypt
{
    const INDEX = 'index.php';

    private static $key = NULL;

    private static function key()
    {
        if (self::$key === NULL)
        {
            $config = array();
            include APPPATH.'config/config.php';
            $raw = isset($config['encryption_key']) ? $config['encryption_key'] : '';
            self::$key = hash('sha256', $raw, TRUE);
        }

        return self::$key;
    }

    public static function encrypt($plain)
    {
        $iv = openssl_random_pseudo_bytes(16);
        $ct = openssl_encrypt((string) $plain, 'aes-256-cbc', self::key(), OPENSSL_RAW_DATA, $iv);
        $mac = substr(hash_hmac('sha256', $iv.$ct, self::key(), TRUE), 0, 16);

        return rtrim(strtr(base64_encode($iv.$ct.$mac), '+/', '-_'), '=');
    }

    public static function decrypt($token)
    {
        $data = base64_decode(strtr($token, '-_', '+/'), TRUE);

        if ($data === FALSE OR strlen($data) < 33)
        {
            return FALSE;
        }

        $mac = substr($data, -16);
        $data = substr($data, 0, -16);
        $iv = substr($data, 0, 16);
        $ct = substr($data, 16);

        if ( ! hash_equals(substr(hash_hmac('sha256', $iv.$ct, self::key(), TRUE), 0, 16), $mac))
        {
            return FALSE;
        }

        $plain = openssl_decrypt($ct, 'aes-256-cbc', self::key(), OPENSSL_RAW_DATA, $iv);

        return $plain === FALSE ? FALSE : $plain;
    }

    public static function decode_request()
    {
        if ( ! isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']))
        {
            return;
        }

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

        if (strpos($path, $_SERVER['SCRIPT_NAME']) === 0)
        {
            $path = substr($path, strlen($_SERVER['SCRIPT_NAME']));
        }
        elseif ($base !== '' && strpos($path, $base.'/'.self::INDEX) === 0)
        {
            $path = substr($path, strlen($base.'/'.self::INDEX));
        }
        elseif ($base !== '' && strpos($path, $base.'/') === 0)
        {
            $path = substr($path, strlen($base) + 1);
        }

        $path = trim($path, '/');

        if ($path === '')
        {
            return;
        }

        $segments = explode('/', $path, 2);
        $plain = self::decrypt($segments[0]);

        if ($plain === FALSE)
        {
            return;
        }

        $route = $plain.(isset($segments[1]) ? '/'.$segments[1] : '');
        $query = '';
        $pos = strpos($_SERVER['REQUEST_URI'], '?');

        if ($pos !== FALSE)
        {
            $query = substr($_SERVER['REQUEST_URI'], $pos);
        }

        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'].($route === '' ? '' : '/'.$route).$query;
        $_SERVER['PATH_INFO'] = $route;
    }
}
