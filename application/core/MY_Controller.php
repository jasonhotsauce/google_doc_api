<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
    protected $viewData;
    public function __construct() {
        parent::__construct();
        $this->viewData = array(); // initiate view data
    }
    
    protected function __showView($contentView) {
        $content = $this->load->view($contentView, $this->viewData, TRUE);
        $this->load->view('home', array('content'=>$content));
    }
}