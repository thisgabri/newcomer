<?php
/**
 * 
 * 
 * @author mattevigo
 * 
 * @project NerdStart
 * @created 10/feb/2009
 */
require_once "config.php";
require_once DB;
require_once SESSION;

$db_wrapper = new DB($db_config);

session_start();
$_SESSION['sid'] = session_id(); 
echo $_SESSION['sid'] . "<br /><br />";

try{
	$session = new Session($db_wrapper, $_SESSION['sid'], 1);
} catch(SessionException $e){
	echo "Sessione in corso<br />";
}
echo "<a href=test_session2.php>prova</a>";
?>
