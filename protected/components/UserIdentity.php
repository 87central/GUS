<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Type: User The user who is logged in.
	 */
	private $_user;
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = User::model()->findByAttributes(array('EMAIL'=>$this->username));
		
		if($user === null ){
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		} else if(!$user->validatePassword($this->password)) {
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		} else {
			$this->errorCode=self::ERROR_NONE;
		}
				
		if($this->errorCode == self::ERROR_NONE){
			$this->_user = $user;
			$this->setPersistentStates(array(
				'isAdmin'=>$user->isAdmin,
				'isDefaultRole'=>$user->isPrinter,
				'isCustomer'=>$this->isCustomer,
				'isLead'=>$this->isLead,
			));			
		}
		return $this->errorCode==self::ERROR_NONE;
	}
	
	public function getId(){
		return $this->_user->ID;
	}
	
	public function getName(){
		return $this->_user->FIRST;
	}
	
	public function getRole(){
		return $this->_user->ROLE;
	}
	
	public function getIsAdmin(){
		return $this->role == User::ADMIN_ROLE;
	}
	
	public function getIsDefaultRole(){
		return $this->role == User::DEFAULT_ROLE;
	}
	
	public function getIsCustomer(){
		return $this->role == User::CUSTOMER_ROLE;
	}
}