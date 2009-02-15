<?php
/********************************************************************************************************
 * Questa classe e' utilizzata per l'interazione con il database e rappresenta un record di una			*
 * qualsiasi delle sue tabelle.																			*
 * 																										*
 * @author NerdRiot 2.0																					*
 * @package NerdStart																					*
 * 																										*
 ********************************************************************************************************/

interface Entity{
	
	public function getJSON();
	
	public function getXML();
	
	public function set($index, $value);
	
	public function commit();
	
	public function store();
	
	public function cancel();
	
}

class EntityException extends Exception{
}
?>
