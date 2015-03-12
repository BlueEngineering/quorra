<?php
/************************************************************************************
 * Entity class: Module for users 
 * 
 * @author		Tim Jaap <tim.jaap@mailbox.tu-berlin.de>
 * @version		0.1 (beta)
 ************************************************************************************/
//
class ec_user {
	// ----- attributes -----
	private $user_id;
	private $user_name;
	private $user_email;
	
	// ----- constructor -----
	/************************************************************************************
	 * Constructor method with parameters to create an user object
	 *
	 * @param	Int		$id
	 * @param	String	$name
	 * @param	String	$email 
	 ************************************************************************************/
	public function __construct( $id, $name, $email ) {
		$this->user_id		= $id;
		$this->user_name	= $name;
		$this->user_email	= $email;
	}
	
	// ----- methods -----
	/************************************************************************************
	 * Method to get id of user
	 *
	 * @return	Int
	 ************************************************************************************/
	public function get_user_id() {
		return $this->user_id;
	}
	
	/************************************************************************************
	 * Method to get name of user
	 * 
	 * @return	String
	 ************************************************************************************/
	public function get_user_name() {
		return $this->user_name;
	}
	
	/************************************************************************************
	 * Method to get email of user
	 *
	 * @return	String
	 ************************************************************************************/
	public function get_user_email() {
		return $this->user_email;
	}
}
?>
