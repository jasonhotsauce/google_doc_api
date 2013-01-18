<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends MY_Controller {
    private $myself; // user object
    public function __construct() {
        parent::__construct();
        $this->myself = new User();
    }
    
    /**
     * Default function at the entry point. 
     */
    public function index() {
        if ($this->myself->getGoogleToken()){
            // We have access to user's data. Rechieve all user files
            $this->viewData['hasToken'] = TRUE;
            $this->viewData['documents'] = $this->myself->getAllFiles();
        } else
            $this->viewData['hasToken'] = FALSE;
        $this->__showView("content");
    }
    
    public function gainToken() {
        try {
            $tokenURL = User::getOAuthURL();
            header('location:'.$tokenURL);
        } catch (ApiSettingException $e) {
            echo $e->getMessage();
        }
    }
    
    public function authorized() {
        $authCode = isset($_GET['code'])?$_GET['code']:null;
        if($authCode){
            $this->myself->gainToken($authCode);
            $data = array('googleToken'=>$this->myself->getGoogleToken(), 'refreshToken'=>$this->myself->getRefreshToken());
            $this->session->set_userdata($data);
            echo '<script type="text/javascript">window.opener.location.reload(); self.close();</script>';
            exit;
        }
    }
    
    public function trashDoc() {
        try {
            $docId = $this->input->post('id');
            $etag = $this->input->post('etag');
            $result = $this->myself->trashFile($docId, $etag);
            if ($result) {
                echo json_encode(array('status'=>'success'));
            }
        } catch (ErrorException $e) {
            echo json_encode(array('status'=>'failure', 'error'=>$e->getMessage().' Code: '.$e->getCode()));    
        }
        
        
    }
}