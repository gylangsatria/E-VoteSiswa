<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/Pathcrypt.php';

function pathcrypt_pre_system()
{
	Pathcrypt::decode_request();
}
