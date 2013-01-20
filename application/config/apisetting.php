<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['google_api'] = array(

        'client_id' => '', // Your app id

        'client_secret' => '', //  Your secret

        'scopes' => 'https://www.googleapis.com/auth/drive.file https://spreadsheets.google.com/feeds/ https://docs.googleusercontent.com/',

        'app_name' => 'Google Docs API Demo',

        'redirect_uri' => '', //  Redirect url after gaining taken

        'chunk_size' => 256*1024,

        'retry_times' => 3

);