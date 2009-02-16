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

// create the new DB
$db_wrapper = new DB($db_config);

echo "<h3>Test for the DBEntity class functions</h3>";
echo "Number of records in the test table: ".DBEntity::count($db_wrapper, "test_table")."<br />";

/**
 * Prova DBEntity
 */
$entity = new DBEntity($db_wrapper, "test_table", "test_id");

//insert a new string value
$entity->set("test_string", "Hello World!");
//insert a new number value
$entity->set("test_number", 4);

//test JSON rappresentation of the data
echo "JSON: " . $entity->getJSON();

//salvo il nuovo record
try{
	$entity->store();
	echo "<br/>New Entity add<br /><br />";
}catch (DBException $e){
	echo $e->getMessage();
}

//cambio un valore dell'oggetto
$entity->set("test_number", 5);

//save changes
$entity->commit();

//delete the entity
$entity->cancel();
?>
