<?php
require_once("config.php");
require_once("DB");

$db_wrapper = new DB($db_config);

$users_query = "CREATE TABLE `$db_wrapper->t_users` (
				`user_id` mediumint(8) unsigned NOT NULL auto_increment,
  				`user_username` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  				`user_password` varchar(40) character set utf8 collate utf8_bin NOT NULL,
  				`user_description` mediumtext character set utf8 collate utf8_bin NOT NULL,
  				`user_msnm` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  				`user_email` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  				`user_address` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  				`user_phone` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  				`user_admin` tinyint(1) unsigned NOT NULL,
  				PRIMARY KEY  (`user_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;";

$sessions_query = "CREATE TABLE `$db_wrapper->t_sessions` (
  					`session_id` varchar(255) NOT NULL,
  					`user_id` varchar(11) NOT NULL,
  					`session_start` int(11) unsigned NOT NULL,
  					`session_time` int(11) unsigned NOT NULL,
  					`session_browser` varchar(255) NOT NULL,
  					`session_ip` varchar(40) NOT NULL,
  					`session_javascript` tinyint(1) NOT NULL,
  					PRIMARY KEY  (`session_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				
$test_query = "CREATE TABLE `test_table` (
  				`test_id` int(32) NOT NULL auto_increment,
  				`test_number` int(11) NOT NULL,
  				`test_string` varchar(24) NOT NULL,
  				PRIMARY KEY  (`test_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=latin1";
				
try{
	$db_wrapper->query($users_query);
	$db_wrapper->query($sessions_query);
	$db_wrapper->query($test_query);
	echo "Database inizializzato con successo. Elimina lo script.";
	
} catch(DBException $e){
	echo "Si sono verificati degli errori nella creazione delle tabelle nel database.<br />
			L'errore riscontrato e': ".$e->getMessage();
}
?>