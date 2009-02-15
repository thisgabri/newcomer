<?php
/**
 * 
 * 
 * @author mattevigo
 * 
 * @project NerdStart
 * @created 10/feb/2009
 */
 
session_start();

//echo "Chiudo la sessione corrente " .$_SESSION['sid'] . "<br />";

//session_destroy();
//unset($_SESSION['sid']);

session_regenerate_id(true);

echo "<a href=test_session.php>ritorna</a>";
?>
