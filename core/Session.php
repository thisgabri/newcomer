<?php
/**
 * 
 * 
 * @author mattevigo
 * 
 * @project NerdStart
 * @created 14/gen/2009
 */
//require_once("Entity.php");
require_once("DBEntity.php");

class Session extends DBEntity{
	
	/**
	 * Crea una nuova sessione. Questa viene poi salvata sul database corrispondente.
	 * 
	 * @param DB $db_wrapper
	 * @param $sid
	 * @param $uid
	 * 
	 * @throws SessionException se esiste gia' una sessione valida con questi parametri
	 * @throws DBException se si sono verificati dei problemi nell'interazione con il DB
	 * 
	 * @todo questo costruttore dovrebbe non essere pubblico in quanto vorrei prevedere una funzione statica
	 * 		che restituisca un oggetto di tipo sessione, questo perche' oltre a gestire i record di sessione
	 * 		devo gestire anche i cookies al browser.
	 */
	public function __construct(DB $db_wrapper, $sid, $uid){
		
		//controllo se esiste gia' una sessione attiva con questo SID
		if(Session::validate($db_wrapper, $sid, $uid, $db_wrapper->session_time)){
			throw new SessionException("La sessione e' valida.");
		}
		
		parent::__construct($db_wrapper, $db_wrapper->prefix() . $db_wrapper->t_sessions, "session_id");
		
		//$this->set("session_id", $sid);
		$this->set("user_id", $uid);
		$this->set("session_start", time());
		$this->set("session_browser", $_SERVER['HTTP_USER_AGENT']);
		$this->set("session_ip", $_SERVER['REMOTE_ADDR']);
		//$this->set("session_javascript", 1);
		//$this->set("session_swf", 1);
		
		$this->store($sid); //salva le modifiche sul database
	}
	
	/**
	 * Verifica l'esistenza nella tabelle delle sessioni di una sessione con questo SID
	 * 
	 * @param DB $db_wrapper
	 * @param $table_name
	 * @param $sid
	 * 
	 * @return true se esiste, false altrimenti
	 * 
	 * @throws DBException per problei con il database
	 */
	public static function exist(DB $db_wrapper, $table_name, $sid){
		
		$mysql_query = "SELECT count(*) FROM $table_name WHERE session_id=$sid";
		$data = mysql_fetch_row($db_wrapper->query($mysql_query));
		
		if($data[0]==0)
			return false;
		else return false;
	}
	
	/**
	 * Convalida una sessione
	 *
	 * @param $sid identificatore univoco di sessione
	 * @param $uid identificatore univoco utente
	 * @param DB $db_wrapper
	 * @param $session_time durata della sessioni, se $session_time vale 0 (default)
	 * 			allora non ci sono limiti alla sessione
	 * 
	 * @throws DBException se si sono verificati problemi nella query
	 * 
	 * @return true se la sessione e' associata allo stesso uid
	 *			false altrimenti
	 */
	public static function validate(DB $db_wrapper, $sid, $uid, $session_time=0){
		
		$mysql_query = "SELECT `user_id`, `session_start` " .
						"FROM `$db_wrapper->t_sessions` " .
						"WHERE `session_id` = '$sid' " .
						"ORDER BY session_start DESC";
						
		$result = $db_wrapper->query($mysql_query);
		
		$row = mysql_fetch_assoc($result);
		
		if($row['user_id'] != $uid)
			return false;
		else if(($session_time > 0) && ($row['session_start'] + $session_time < time()))
			return false;
		else return true;
	}
	
	/**
	 * Verifica la disponibilita del javascript via php
	 * 
	 * @return true se javascript e' abilitato
	 * 			false altrimenti
	 */
	public static function acceptJavascript(){
		
		if(!isset($_GET['checkjs'])){
			
			$querystring= @eregi_replace(
				$_SERVER['DOCUMENT_ROOT'],
				'http://'.$_SERVER['HTTP_HOST'],
				$_SERVER['SCRIPT_FILENAME']
			);
			
			echo '<script type="text/javascript">window.location.href="'
			.$querystring.'?checkjs=y";</script>';
			
			echo '<noscript><meta http-equiv="refresh" content="0; url='
			.$querystring.'?checkjs=n"/></noscript>';
		}
		
		if($_GET['checkjs']=='y')
			return true;
		
		if($_GET['checkjs']=='n')
			return false;
	}
	
	/**
	 * Restituisce un oggetto di tipo sessione e setta il cookie nel browser.
	 * Se una sessione e' gia' attiva allora una nuova sessione viene creata.
	 */
	public static function getNew(){
		
	}
	
	//	public static function store($sid ,$uid, DB $db_wrapper){
	//		
	//		$time = time();
	//		
	//		$mysql_query = "INSERT INTO `{$db_wrapper->name()}`.`$db_wrapper->t_sessions` (
	//							`session_id`," .
	//							"`user_id`," .
	//							"`session_start`," .
	//							"`session_browser`," .
	//							"`session_ip`" .
	//							"`session_javascript`".
	//							"`session_swf`".
	//						")VALUES (" .
	//							"'$sid'," .
	//							"$uid," .
	//							"$time," .
	//							"'{$_SERVER['HTTP_USER_AGENT']}'," .
	//							"'{$_SERVER['REMOTE_ADDR']}'" .
	//						");";
	//		
	//		$result = $db_wrapper->query($mysql_query);
	//		
	//		if(!$result)
	//			throw new DBException("Session::store(): Errore di connessione al database");
	//	}
}

class SessionException extends EntityException{
	
	function __construct($message){
		parent::__construct($message);
	}
}
?>
