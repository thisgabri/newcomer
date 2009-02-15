<?php
/********************************************************************************************************
 * Classe DB per la creazione del db_wrapper, implementa le funzionalita' del database					*
 * 																										*
 * @author NerdRiot 2.0																					*
 * @package PHPure																						*
 * 																										*
 ********************************************************************************************************/
class DB{
	
	// variabili di ambiente
	private $dbms;
	private $host;
	private $port;
	private $name;	# nome del database
	private $user;
	private $pw;
	private $prefix;
	private $connection = NULL;
	
	// nomi tabelle
	public $t_users = "users";
	public $t_sessions = "sessions";
	
	// sessione
	public $session_time = 0;
	
	/**
	 * Costruttore di DB
	 *
	 * @param array $config array di configurazione del database
	 * @require il formato dell'array deve essere (host, name, user, pw, table_prefix)
	 */
	function __construct($db_config, $session_time=0){
		$this->dbms = $db_config['dbms'];
		$this->host = $db_config['host'];
		$this->port = $db_config['port'];
		$this->name = $db_config['name'];
		$this->user = $db_config['user'];
		$this->pw = $db_config['pw'];
		$this->prefix = $db_config['prefix'];
		
		$this->t_users = $this->prefix . $db_config['t_users'];
		$this->t_sessions = $this->prefix . $db_config['t_sessions'];
		
		$this->session_time = $session_time;
	}
	
	/**
	 * Nome del database
	 *
	 * @return la stringa con il nome del database
	 */
	public function name(){
		return $this->name;
	}
	
	/**
	 * Prefisso dei campi del database
	 * 
	 * @return la stringa con il prefisso per questo database
	 */
	public function prefix(){
		if(isset($this->prefix))
			return $this->prefix;
		else return "";
	}
	
	/**
	 * Invia una query al database
	 *
	 * @param string $mysql_query la query da inviare
	 * 
	 * @return il risultato della query
	 * @throws DBException se e' occorso un errore
	 */
	function query($mysql_query){
		$this->connect();
		mysql_select_db($this->name);
		$result = mysql_query($mysql_query);
		
		if(!$result) throw new DBException(mysql_error());
		return $result;
	}
	
	/**
	 * Connessione al database
	 *
	 */
	function connect(){
		$this->connection = mysql_connect($this->host, $this->user, $this->pw) 
				or die("Connessione non riuscita: " . mysql_error());
	}
	
	/**
	 * Chiude la connessione al database
	 * 
	 */
	function disconnect(){
		if($this->connection = null)
			return;
		mysql_close($this->connection);
	}
	
	/**
	 * Link al socket
	 *
	 * @return link della connessione al database
	 */
	function getLink(){
		return $this->connection;
	}
	
	/**
	 * Distruttore
	 *
	 */
	function __deconstruct(){
		//TODO
	}
	
	public function toString(){
		return "DB - $this->name";
	}
}

class DBException extends Exception{}
?>