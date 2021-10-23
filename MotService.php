<?php
require_once 'conexion.php';
require_once 'lib/nusoap.php';
	//Función que inserta un nuevo registo
	function  insertmoto($nombre,$modelo,$marca,$cilindrada){
	try{
		$conexion=new conexion();
		$consulta=$conexion->prepare("INSERT INTO motos(Nombre,Modelo,Marca,Cilindrada)
		       VALUES(:nombre,:modelo,:marca,:cilindrada)");

		$consulta -> bindParam(":nombre",$nombre,PDO::PARAM_STR);
		$consulta -> bindParam(":modelo",$modelo,PDO::PARAM_STR);
		$consulta -> bindParam(":marca",$marca,PDO::PARAM_STR);
		$consulta -> bindParam(":cilindrada",$cilindrada,PDO::PARAM_STR);
		$consulta -> execute();
		$ultimoId=$conexion->lastInsertId();
		return join(",",array($ultimoId));


	} catch (Exception $e){
		return join(",",array(false));
	}
  } //Fin de la función insertar
  	//Función que devuelve los registros
  	function leermoto(){
  	try{
  		$conexion=new conexion();
		$consulta1=$conexion->prepare("SELECT Id,Nombre,Modelo,Marca,Cilindrada FROM motos");
		$consulta1 -> execute();
		while ($tabla = $consulta1->fetch(PDO::FETCH_ASSOC)){
			return $tabla;
		}
		

		//}
  	}catch(Exception $e){
  		echo 'Error='.$e->getMessage();
  	}

  	} //Fin de la función
  	//Función para actualizar los registros
  	function actualizarmoto($id,$nombre,$modelo,$marca,$cilindrada){
  	try{
  		$conexion=new conexion();
		$consulta2=$conexion->prepare("UPDATE motos SET Nombre = :nombre , Modelo = :modelo, Marca = :marca , Cilindrada = :cilindrada WHERE Id = :id");

		$consulta2 -> bindParam(":nombre",$nombre,PDO::PARAM_STR);
		$consulta2 -> bindParam(":modelo",$modelo,PDO::PARAM_STR);
		$consulta2 -> bindParam(":marca",$marca,PDO::PARAM_STR);
		$consulta2 -> bindParam(":cilindrada",$cilindrada,PDO::PARAM_STR);
		$consulta2 -> bindParam(":id",$id,PDO::PARAM_INT);
		$consulta2 -> execute();
		$resultado = $consulta2->rowCount();
		if($resultado > 0){
			return join(",",array(true));
		}
		else{
			return join(",",array(false));	
		}

  	}catch(Exception $e){
  		return join(",",array(false));	
  	}

  	}//Fin de la función actualiza
  	//Función delete
  	function eliminarmoto($id){
  	try{
  		$conexion=new conexion();
		$consulta3=$conexion->prepare("DELETE FROM motos WHERE Id =?");
		$consulta3->execute(array($id));
		$resultadod = $consulta3->rowCount();

		if($resultadod > 0){
			return join(",",array(true));
		}
		else{
			return join(",",array(false));	
		}

  	}catch(Exception $e){
  		return join(",",array(false));	
  	} 
  	}//fin función delete
  	


$server = new nusoap_server();
$server -> configureWSDL("Motoservice", "urn:motoservice");

$server->wsdl->addComplexType(
'paramsIn',
'complexType',
'struct',
'all',
'',
array());

$server->wsdl->addComplexType(
'paramsOut',
'complexType',
'struct',
'all',
'',
array(
'Id'=>array('name'=> 'Id', 'type' => 'xsd:string'),
'Nombre'=>array('name'=> 'Nombre', 'type' => 'xsd:string'),
'Modelo'=>array('name'=> 'Modelo', 'type' => 'xsd:string'),
'Marca'=>array('name'=> 'Marca', 'type' => 'xsd:string'),
'Cilindrada'=>array('name'=> 'Cilindrada', 'type' => 'xsd:string')	
)
);

$server->register("leermoto",
        array(),
        array("return" => "tns:paramsOut"),
        "urn:motoservice",
        "urn:motoservice#leermoto",
        "rpc",
        "encoded",
        "Metodo que devuelve los registros de motos");

$server->register("insertmoto",
        array("nombre" => "xsd:string","modelo" => "xsd:string","marca" => "xsd:string","cilindrada" => "xsd:string"),
     
        array("return" => "xsd:string"),
        "urn:motoservice",
        "urn:motoservice#insertar",
        "rpc",
        "encoded",
        "Metodo que inserta una moto");

$server->register("actualizarmoto",
        array("id" => "xsd:integer", "nombre" => "xsd:string","modelo" => "xsd:string","marca" => "xsd:string","cilindrada" => "xsd:string"),
        array("return" => "xsd:string"),
        "urn:motoservice",
        "urn:motoservice#actualizarmoto",
        "rpc",
        "encoded",
        "Metodo que actualiza un registro");

$server->register("eliminarmoto",
        array("id" => "xsd:integer"),
        array("return" => "xsd:string"),
        "urn:motoservice",
        "urn:motoservice#eliminarmoto",
        "rpc",
        "encoded",
        "Metodo que elimina los registros de motos");


$post = file_get_contents('php://input');
$server->service($post);
?>