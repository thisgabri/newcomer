<?php
/*************************************************************************************************************
 * File contenente tutte le costanti per la configurazione e l'array contenente tutti i parametri di		 *
 * configurazione del database. L'utilizzo di variabili di istanza e' deprecata e quindi potrebbe non essere *
 * piu' supportata nelle versioni successive.																 *
 *  																										 *
 * @var costanti di configurazione (versione e nome di default del database									 *
 * @var "paths" 		indirizzi assoluti dei file appartenenti al core									 *
 * @var "scripts" 		indirizzi assoluti dei file di script												 *
 * @var "directories" 	indirizzi assoluti per le directories dove verranno memorizzate le foto uploadate	 *
 * @var "web directories" indirizzi web delle stesse direcoties (da utilizzare nelle pagine)				 *
 * @var $db_wrapper		array contenente i parametri di configurazione del database							 *
 * @var "formati"		costanti per la definizione dei formati												 *
 * @var $allowed_image_type array conteneti i mime type delle immagini accettate							 *
 * @var "opzioni"																							 *
 * 																											 *
 * @author NerdRiot 2.0																						 *
 * @package NerdStart																						 *
 * 																											 *
 ************************************************************************************************************/

// costanti
define("VERSION", '0.1');					# versione corrente
define("DEFAULT_DBNAME", '');	# nome di default del database

// core
define("DBENTITY", $_SERVER['DOCUMENT_ROOT']."/core/DBEntity.php");
define("SESSION", $_SERVER['DOCUMENT_ROOT']."/core/Session.php");
define("DB", $_SERVER['DOCUMENT_ROOT']."/core/DB.php");
define("CORE_PATH", $_SERVER['DOCUMENT_ROOT']."/core");

//scripts
define("LOGIN_FILE", $_SERVER['SERVER_NAME']."/login.php");
define("LOGOUT_FILE", $_SERVER['SERVER_NAME']."/logout.php");
define("CONTENT_MANAGER",$_SERVER['DOCUMENT_ROOT']."/bin/content_manager.php");

//directories

//web directories

// variabili di configurazione del database
$db_config = array(
	'dbms'			=>	'',
	'host'			=>	'localhost',
	'port'			=>	'',
	'name'			=>	'nerdstart',
	'user'			=>	'root',
	'pw'			=>	'porcupine',
	'prefix' 		=> '',
	't_users'		=>	'users',
	't_sessions'	=>	'sessions'
	//inserire qua i nomi di eventuali tabelle
);

// formati
define("HASH_ALGO", "md5");					# algoritmo hash
define("DATE_FORMAT_GNU", "j F Y");			# formato data secondo lo standard GNU (usato in strtotime)
define("DATE_FORMAT", "j/m/Y \o\\r\e G:i");	# formato data

// opzioni
define("SESSION_TIME", 1200);		# durata sessione in secondi (20 min)
define("ANONYMOUS_ID", 0);			# user_id di default per utente anonimo non autenticato
?>