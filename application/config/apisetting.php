<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['google_api'] = array(

        'client_id' => '441951789615.apps.googleusercontent.com',

        'client_secret' => 'wWAjhNABA2p69TWniLFOeRv4',

        'scopes' => 'https://www.googleapis.com/auth/drive.file https://spreadsheets.google.com/feeds/ https://docs.googleusercontent.com/',

        'app_name' => 'Google Docs API Demo',

        'redirect_uri' => 'http://localhost/~jasonhotsauce/Google_Docs/index.php/home/authorized',

        'chunk_size' => 256*1024,

        'retry_times' => 3

);