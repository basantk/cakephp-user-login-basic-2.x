<?php
class User extends AppModel {

   // public $belongsTo = array( 'Group' => array( 'className' => 'Group', 'foreignKey' => 'group_id'));
	
    public $validate = array(
        'username' => array( 
		'required' => array(
                'rule' => array('notBlank'),
                'message' => 'A username is required'
            ),
		'between' => array(
                'rule'    => array('between', 5, 15),
                'message' => 'Between 5 to 15 characters'
            )
           ,  'unique' => array(
				'rule' => 'isUnique',
				'message' => 'User already registered!'
			)
            
        ),
        'password' => array( 
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'A password is required'
            ),'between' => array(
                'rule'    => array('between', 5, 15),
                'message' => 'Between 5 to 15 characters'
            )
        )
    );


	public function beforeSave($options = array()) {
	    if (isset($this->data[$this->alias]['password'])) {
		$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
	    }
	    return true;
	}

}
?>
