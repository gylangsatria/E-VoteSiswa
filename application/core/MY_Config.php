<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/Pathcrypt.php';

class MY_Config extends CI_Config
{
    public function base_url($uri = '', $protocol = NULL)
    {
        $trimmed = ltrim((string) $uri, '/');

        if (strpos($trimmed, Pathcrypt::INDEX) === 0)
        {
            $route = ltrim(substr($trimmed, strlen(Pathcrypt::INDEX)), '/');

            return $this->site_url($route, $protocol);
        }

        return parent::base_url($uri, $protocol);
    }

    public function site_url($uri = '', $protocol = NULL)
    {
        if (is_string($uri) && stripos($uri, '://') !== FALSE)
        {
            return $uri;
        }

        $route = is_array($uri) ? implode('/', $uri) : trim((string) $uri, '/');

        return parent::site_url(Pathcrypt::encrypt($route), $protocol);
    }
}
