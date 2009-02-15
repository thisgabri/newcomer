<?php
/********************************************************************************************************
 * Questa classe e' utilizzata per l'interazione con il database e rappresenta un record di una			*
 * qualsiasi delle sue tabelle.																			*
 * 																										*
 * @author NerdRiot 2.0																					*
 * @package NerdStart																					*
 * 																										*
 ********************************************************************************************************/
require_once("Entity.php");

class DBEntity implements Entity{
	/**
	 * @var $primary_key il nome del campo che identifica la chiave primaria 
	 * @var $id il valore della chiave primaria
	 * 
	 * @var $ext_id 
	 */
	private $id = null;
	private $ext_id = null;
	
	public $values = Array();
	public $changes = Array();
	public $is_new = true;
	
	public $db_wrapper;
	public $table = null;
	public $primary_key;
	
	// commit
	private $commit_query_update = "UPDATE ";
	private $commit_query_set = "SET ";
	private $commit_query_where = "WHERE ";
	
	/**
	 * Costruttore
	 * 
	 * @param DB $db_wrapper
	 * @param $table nome della tabella
	 * @param $primary_key il nome del campo della chiave primaria
	 * 
	 * @throws DBException se si sono verificati problemi durante l'interrogazione con il database
	 * 
	 * @todo implementazione di chiavi primarie multiple da realizzare con due array $pk e $kv che contengono
	 * 			i nomi e i valori delle chiavi primarie.
	 */
	function __construct(DB $db_wrapper, $table, $primary_key, $id=null){
		$this->db_wrapper = $db_wrapper;
		$this->table = $table;
		$this->primary_key = $primary_key;
		
		if(isset($id)){
			$mysql_query = 	"SELECT * " .
				"FROM $table " .
				"WHERE $primary_key=$id ";
			
			$result = $this->db_wrapper->query($mysql_query);
			$data_array = mysql_fetch_assoc($result);
			
			foreach($data_array as $key => $value){
				$this->values[$key] = $value;
			}
			
			$this->is_new = false;
		}
	}
	
	/**
	 * Restituisce l' XML realativo a questa entita'.
	 * I nomi dei tag sono i nomi del relativo campo.
	 * 
	 * @todo metodo ancora da testare
	 */
	public function getXML(){
		
		$xml = "<$this->table>";
		
		foreach($this->values as $key => $value){
			$xml .= "<$key>$value</$key>";
		}
		
		$xml .= "</$this->table>";
		
		return $xml;
	}
	
	/**
	 * 
	 * @return una stringa in formato JSON
	 */
	public function getJSON(){
		
		$json = "{";
		$nelem = count($this->values);
		$i = 0;
		
		foreach($this->values as $key => $value){
			$json .= "$key:$value";
			$i++;
			if($i<$nelem) $json.= ", ";
		}
		
		return $json."}";
	}
	
	/**
	 * Setta/Modifica il valore di un campo del record
	 * 
	 * @param $index
	 * @param $value
	 */
	public function set($index, $value){
		
		$this->values[$index] = $value;
		$this->changes[$index] = true;
	}
	
	/**
	 * Applica le modifiche apportate al record individuato dalla chiave primaria $primary_key
	 */
	public function commit(){
		
		$toSet = $this->commit_query_set;
		$first = TRUE; # variabile usata per posizionare le virgole
		
		foreach($this->changes as $key => $val){ # iterazione all'interno dell'array
			
			if($val){
				
				if($first) {					#
					$first = FALSE;				# Corretto posizionamento della virgola
				} else $toSet = $toSet . ", ";	#
				
				# viene aggiornato solo il valore cambiato
				$toSet = $toSet . "`" . $key . "`='" . $this->values[$key] . "'";
			}
			
		}
		
		$toSet = $toSet . " ";
		//echo $toSet . "<br />";	# stampa di verifica
		
		$query = $this->commit_query_update . $this->table . " "
			. $toSet 
			. $this->commit_query_where . "`{$this->table}`.`{$this->primary_key}`=" . $this->id 
			. " LIMIT 1";
		
		//echo $query . "<br />";	# stampa di verifica
		try{
			$this->db_wrapper->query($query);
		} catch (DBException $e){ 
			throw new EntityException($e->getMessage);
		}
		
		$this->reset();
	}
	
	/**
	 * Inserisce nella tabella un nuovo record con parametri settati in precedenza
	 * 
	 * @param $id il valore della chiave primaria dell'elemento che sta per essere scritto nel database
	 * 			di default questo valore e' impostato a null, in questo caso tratta l'id corrispondente
	 * 			come AUTO_INCREMENT
	 * 
	 * @trows EntityException se la chiave primaria e' gie' definita oppure se e' occorso un errore
	 * 							durante la scrittura nel database.
	 */
	public function store($id=null){
		
		$mysql_query_insert = "INSERT INTO `{$this->db_wrapper->name()}`.`$this->table` (";
		$mysql_query_values = ")VALUES (";
		
		//echo "sid: ".$id . "<br />";
		
		$mysql_query_insert .= $this->primary_key . ", ";
		if(is_null($id)) $mysql_query_values .= "NULL, ";
		else {
			if(is_string($id)) $mysql_query_values .= "'$id', ";
			else $mysql_query_values .= "$id, ";
		}
		
		$size = count($this->values);
		
		$i = 1;
		foreach($this->values as $key => $value){
			
			$mysql_query_insert .= $key;
			
			if(is_string($value)) $mysql_query_values .= "'$value'";
			else $mysql_query_values .= $value;
			
			if($i < $size){
				$mysql_query_insert .= ", ";
				$mysql_query_values .= ", ";
			}
			$i++;
		}
		
		$mysql_query = $mysql_query_insert . $mysql_query_values . ");";
		//echo $mysql_query;
		
		try{
			//scrivo i cambiamenti sul database
			$result = $this->db_wrapper->query($mysql_query);
			if(is_null($id)) $this->id = mysql_insert_id();
			
		} catch (DBException $e){
			throw new EntityException($e->getMessage());
		}
		
		//resetto tutti i valori ausiliari
		$this->reset();
	}
	
	/**
	 * Elimina questo record dal database
	 * 
	 * @todo
	 */
	public function cancel(){
		//TODO
		
	}
	
	/**
	 * Resetta tutte le variabili di ausilio.
	 */
	private function reset(){
		
		$this->commit_query_set = "SET ";
		$this->commit_query_update = "UPDATE ";
		$this->commit_query_where = "WHERE ";
		
		foreach ( $this->changes as $key => $value ) {
			$this->changes[$key] = false;
		}
	}
	
	/**
	 * Conta il numero di record presenti in questa tabella
	 * 
	 * @param DB $db_wrapper
	 * @param $table_name
	 * 
	 * @return il numero di record nel database
	 */
	public static function count(DB $db_wrapper, $table_name){
		
		$mysql_query = "SELECT count(*) FROM ".$table_name." WHERE 1";
		$data = mysql_fetch_row($db_wrapper->query($mysql_query));
		
		return $data[0];
	}
	
	/**
	 * Restituisce il record nella tabella $table_name identificato dalla chiave $id
	 * 
	 * @param DB $db_wrapper
	 * @param $table_name
	 * @param $id
	 * 
	 * @todo
	 */
	public static function getFromId(DB $db_wrapper, $table_name, $id){
		//TODO
		
	}
	
	/**
	 * Restituisce l'XML relativo al record nella tabella $table_name identificato dalla chiave $id
	 * 
	 * @param DB $db_wrapper
	 * @param $table_name
	 * @param $primary_key
	 * @param $id
	 * 
	 * @return il contenuto xml realativo a la record identificato da $id
	 */
	public static function getXMLFromId(DB $db_wrapper, $table_name, $prymary_key ,$id){
		
		$mysql_query = 	"SELECT * " .
			"FROM $table_name " .
			"WHERE $primary_key=$id ";
		
		$xml = "<$table_name>";
		
		try{			
			$data = mysql_fetch_assoc($db_wrapper->query($mysql_query));
			
			foreach($data as $key => $value){
				$xml .= "<$key>$value</$key>";
			}
		}catch (DBException $e){
			throw new EntityException($e->getMessage());
		}
		
		$xml .= "</$table_name>";
		
		return $xml;
	}
	
}
?>
