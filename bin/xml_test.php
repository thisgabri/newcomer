<?php
/**
 * 
 * 
 * @author mattevigo
 * 
 * @project NerdStart
 * @created 09/feb/2009
 */
require_once("../config.php");
require_once(DB);
require_once(DBENTITY);

header("content-type: text/xml");

$db_wrapper = new DB($db_config);
$entity = new DBEntity($db_wrapper, "nerdstart_prova", "nerd_id", 3);

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" . $entity->getXML();
?>
