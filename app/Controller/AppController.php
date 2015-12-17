<?php
App::uses('Controller', 'Controller');

class AppController extends Controller
{
    public $user_permission_data = array();
    public $_Configuration;
    var $components = array
    (
        'Session',
        'DebugKit.Toolbar',
        'Auth',
        'RequestHandler',
        'Email',
        'Cookie'
        //'Gzip.Gzip'
    );
    var $helpers = array
    (
        'Form',
        'Html',
        'Session',
        'Time',
    );
   
    public function beforeFilter()
    {


      
        $this->Auth->autoRedirect = true;
        $this->Auth->authError = 'Sorry, you are not authorized to view that page.';
        $this->Auth->loginError = 'invalid username and password combination.';

        $this->Auth->loginAction = array
        (
            'controller' => 'users',
            'action' => 'login',
            'admin' => true
        );

        $this->Auth->logoutRedirect = array
        (
            'controller' => 'users',
            'action' => 'login',
            'admin' => true
        );

        $this->Auth->loginRedirect = array
        (
            'controller' => 'users',
            'action' => 'dashboard',
            'admin' => true
        );
        $this->Auth->authenticate = array(
            'all' => array(
                'scope' => array('User.status' => 1)
            ),
            'Form'
        );

        $this->Auth->fields = array('username' => 'username', 'password' => 'password');
        $this->Auth->authorize = 'Controller';

        if ((isset($this->params['prefix']) && ($this->params['prefix'] == 'admin'))) {
           
        }
       // $ckeditorClass = 'CKEDITOR';
        //$this->set('ckeditorClass', $ckeditorClass);

        //$this->_setLanguage();
        //$this->_loadConfiguration();
       
    }

    

    private function _loadConfiguration()
    {
        $this->loadModel('Configuration');
        $this->_Configuration = $this->Configuration->find('list',
            array('fields' => array('key', 'value')));
        $this->set('configuration', $this->_Configuration);
        //return $this->_Configuration;
    }

    private function _setLanguage()
    {
        //if the cookie was previously set, and Config.language has not been set
        //write the Config.language with the value from the Cookie
        if ($this->Cookie->read('lang') && !$this->Session->check('Config.language')) {
            $this->Session->write('Config.language', $this->Cookie->read('lang'));
        } //if the user clicked the language URL
        else {
            if (isset($this->params['language']) &&
                ($this->params['language'] != $this->Session->read('Config.language'))
            ) {
                //then update the value in Session and the one in Cookie
                $this->Session->write('Config.language', $this->params['language']);
                $this->Cookie->write('lang', $this->params['language'], false, '20 days');
            }
        }
    }

    //override redirect
    public function redirect($url, $status = null, $exit = true)
    {
        if (!isset($url['language']) && $this->Session->check('Config.language')) {
            $url['language'] = $this->Session->read('Config.language');
        }
        parent::redirect($url, $status, $exit);
    }

    function isAuthorized()
    {
        $user_perm = array();
        $user_data = array();
        $user_data = $this->user_permission_data;

        $find_data = $this->action;
        //echo $user_data; die;
        if ((isset($this->params['prefix']) && ($this->params['prefix'] == 'admin'))) {
            if (!isset($user_data[$this->params['controller']])) {
                $user_data = array("users" => array("action"));
                $user_perm = $user_data;
            } else {
                $user_perm = $user_data[$this->params['controller']]['action'];
            }

            if (in_array($find_data, $user_perm) || $find_data == 'admin_dashboard') {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    function validateLogin()
    {

        $logarr = $this->Session->read('CLIENT');
        if (count($logarr) < 1) {
//            $this->Session->setFlash(__('Session Expired'), 'default', array('class' => 'errors'));
            flash()->overlay('Hmmm... Expired Session', 'Your session has expired. Please login.', 'info');
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
    }

    public function isUserLoggedIn()
    {
        $sUser = $this->Session->read('LOGINDATA');
        if (count($sUser) < 1 || empty($sUser)) {
            return false;
        }
        return true;
    }

    public function imageconfig()
    {
        $this->loadModel('GalleryConfiguration');
        $conimage = $this->GalleryConfiguration->find('all',
            array('conditions' => array('code' => 'file_type')));
        return $conimage;

    }

   

   

    public function afterFilter(){
        // clear flash message
        $this->Session->delete('sweetFlash_message_overlay');
        $this->Session->delete('sweetFlash_message');
    }
}