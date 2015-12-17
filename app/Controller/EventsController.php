<?php
App::uses('AppController', 'Controller');
class EventsController extends AppController{
	public $uses = array();
	public function beforeFilter(){
		parent::beforeFilter();
		//$this->Auth->allow();
	}
	public function index(){
		
	}
	public function getticket(){
		pr($_POST);
		
	}
}
