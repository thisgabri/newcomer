<?php
/**
 * 
 * 
 * @author mattevigo
 * 
 * @project NerdStart
 * @created 10/dic/2008
 */
 
require_once "config.php";
require_once DB;
require_once DBENTITY;
require_once SESSION;

$db_wrapper = new DB($db_config);

/**
 * Prova Session
 */
if(Session::acceptJavascript())
	echo "Javascript abilitato<br />";
else echo "Javascript non abilitato<br />";

echo "<h3>Prova funzioni statiche della classe DBEntity</h3>";
echo "Numero di record nella tabella di prova: ".DBEntity::count($db_wrapper, "nerdstart_prova")."<br />";

/**
 * Prova DBEntity
 */
$entity = new DBEntity($db_wrapper, "nerdstart_prova", "nerd_id");

//setto un nuovo valore
$entity->set("prova_testo", "Hello World!");
$entity->set("prova_numero", 4);

//prova JSON
echo "JSON: " . $entity->getJSON();

//salvo il nuovo record
try{
	$entity->store();
	echo "<br/>Elemento aggiunto<br /><br />";
}catch (DBException $e){
	echo $e->getMessage();
}

//cambio un valore dell'oggetto
$entity->set("prova_numero", 5);

//salvo i cambiamenti
$entity->commit();

$entity->cancel();
?>
