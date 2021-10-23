<?php
class conexion extends PDO{
	
	private $tipo_de_base='mysql';
	private $host='localhost';
	private $nombre_base='motocicleta';
	private $usuario='root';
	private $contraseña='';
	public function __construct(){
	
	try {
	   parent::__construct("{$this->tipo_de_base}:dbname={$this->nombre_base};host={$this->host};charset=utf8",$this->usuario,$this->contraseña);
	} catch (PDOException $e){
	
	  echo 'Error='.$e->getMessage();
	   }
	
	}
}

?>