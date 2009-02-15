<?php
/**
 * 
 * 
 * @author mattevigo
 * 
 * @project NerdStart
 * @created 15/feb/2009
 */
require_once("DBEntity.php");

class User extends DBEntity{
	
	/**
	 * Costruttore
	 */
	public function __construct(DB $db_wrapper, $uid){
		
		parent::__construct($db_wrapper, $db_wrapper->prefix . $db_wrapper->t_users, "user_id", $uid);	
	}
	
	/**
	 * Cambia la password utente dopo aver verificato la validita' della vecchia password
	 * 
	 * @param object $old_password
	 * @param object $new_password
	 * 
	 * @throws DBException per problemi con il database
	 * @throws EntityException se la vecchia password inserita e' sbagliata
	 */
	public function setPassword($old_password, $new_password){
		
		$mysql_query = "SELECT `user_password`
						FROM `{$this->$db_wrapper->name()}`.`$db_wrapper->t_users`
						WHERE `user_id`=$this->user_id";
		
		$result = $this->db_wrapper->query($mysql_query);
		$row = mysql_fetch_row($result);
		//echo $row[0] . "<br />";
		//echo $old_password . "<br />";

		if(strcmp($old_password, $row[0]) == 0){
				
			$mysql_query = "UPDATE `{$db_wrapper->name()}`.`$db_wrapper->t_users` 
							SET `user_password`='$new_password'
							WHERE `user_id`={$this->id()}";
				
			$db_wrapper->query($mysql_query);
				
		} else throw new EntityException("Password sbagliata");
	}
	
	public function setDescription($new_description){
		$this->set('user_description', $new_description);
	}
	
	public function setMsn($new_msn){
		$this->set('user_msnm', $new_msn);
	}
	
	public function setEmail($new_email){
		$this->set('user_email', $new_email);
	}
	
	public function setAddress($new_address){
		$this->set('user_address', $new_address);
	}
	
	public function setPhone($new_phone){
		$this->set('user_phone', $new_phone);
	}
	
	/**
	 * Funzione di login, verifica username e password e se questi coincidono con i relativi valori del database 
	 * viene restituito un oggetto di tipo <code>User</code> relativo all'utente
	 * 
	 * @param DB $db_wrapper
	 * @param object $username
	 * @param object $password
	 * 
	 * @throws 	DBException se si sono verificati problemi con il database
	 * 			LoginException 
	 * @return un  nuovo <code>User</code>
	 */
	public static function login(DB $db_wrapper, $username, $password){
		
		$user = null;
		$db_query = "	SELECT `user_id` , `user_password`, `user_admin`
						FROM `$db_wrapper->t_users`
						WHERE `user_username` = '$username'";

		$result = $db_wrapper->query($db_query);
		$row = mysql_fetch_row($result);
		//echo "result: " . count($row) . "<br />";

		if(count($row) != 3){
			throw new LoginException("Username non valido");

		} else if(strcmp($password, $row[1]) == 0){

			if($row[2] == 1) $user = new Admin($db_wrapper, $row[0]);
			if($row[2] == 0) $user = new User($db_wrapper, $row[0]);

		} else throw new LoginException("Password errata");
		
		return $user;
	}
}

class Admin extends User{
	
}

class LoginException extends Exception{
	function __construct($message){
		parent::__construct($message);
	}
}
?>
